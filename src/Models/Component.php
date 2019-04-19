<?php

namespace Jamesyps\Myriad\Models;

use DomainException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use JsonSerializable;
use Jamesyps\Myriad\Contracts\ComponentInterface;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class Component implements ComponentInterface, Arrayable, JsonSerializable, Jsonable
{
    /**
     * @var string
     */
    private $fullPathToView;

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    private $fileSystem;

    /**
     * Attributes that are exposed to JSON / Array
     *
     * @var array
     */
    private $visibleAttributes = [
        'key',
        'name',
        'source',
        'namespace',
        'parent',
        'attributes',
        'slots',
        'variables',
        'text',
        'viewPath',
    ];

    /**
     * Component constructor.
     *
     * @param string $fullPathToView
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(string $fullPathToView)
    {
        $this->fullPathToView = $fullPathToView;
        $this->fileSystem = app()->make(Filesystem::class);
    }

    /**
     * Humanised name of the blade file
     *
     * @return string
     */
    public function getName(): string
    {
        $filename = basename($this->fullPathToView);

        return strtolower(
            str_replace(['-', '_', '.blade.php'], [' ', ' ', ''], $filename)
        );
    }

    /**
     * Returns the path of the component relative to where Myriad is scanning
     *
     * @return string
     */
    private function getRelativePath(): string
    {
        return trim(
            dirname(str_replace(config('myriad.directory'), '', $this->fullPathToView)),
            '/'
        );
    }

    /**
     * Returns the path of the component relative to the views directory
     *
     * @return string
     */
    private function getViewDirectoryPath(): string
    {
        $path = collect(config('view.paths'))
            ->map(function ($path) {
                return realpath($path);
            })
            ->sortByDesc(function ($path) {
                return strlen($path);
            })
            ->filter(function ($path) {
                return Str::contains(config('myriad.directory'), $path);
            })
            ->first();

        return trim(
            dirname(str_replace($path, '', $this->fullPathToView)),
            '/'
        );
    }

    /**
     * Returns the unique key based on the component path
     *
     * @return string
     */
    public function getKey(): string
    {
        $directory = str_replace(DIRECTORY_SEPARATOR, '.', $this->getRelativePath());

        $filename = str_replace('.blade.php', '', basename($this->fullPathToView));

        return ltrim($directory . '.' . $filename, '.');
    }

    /**
     * Returns the view path that can be used within blade include / component directives
     *
     * @return string
     */
    public function getViewPath(): string
    {
        $directory = str_replace(DIRECTORY_SEPARATOR, '.', $this->getViewDirectoryPath());

        $filename = str_replace('.blade.php', '', basename($this->fullPathToView));

        return ltrim($directory . '.' . $filename, '.');
    }

    /**
     * Returns the raw source code of the blade file
     *
     * @param bool $comments
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getSource(bool $comments = false): string
    {
        $path = $this->fullPathToView;

        if (!$this->fileSystem->exists($path)) {
            throw new DomainException('The file [' . basename($path) . '] does not exist at the given path [' . $path . ']');
        }

        $contents = $this->fileSystem->get($path);

        return trim($comments ? $contents : $this->stripBladeComments($contents));
    }

    /**
     * Removes blade comments from the source code
     *
     * @param string $source
     *
     * @return string
     */
    private function stripBladeComments(string $source): string
    {
        $pattern = $this->getBladeCommentsRegex();

        return preg_replace($pattern, '', $source);
    }

    /**
     * Generates the comment regular expression for blade comments syntax
     *
     * @return string
     */
    private function getBladeCommentsRegex(): string
    {
        return sprintf('/%s--(.*?)--%s/s', '{{', '}}');
    }

    /**
     * Returns the raw source code without removing comments
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getSourceWithComments(): string
    {
        return $this->getSource(true);
    }

    /**
     * Returns the namespace (the parent directory path)
     *
     * @return string
     */
    public function getNamespace(): string
    {
        $directory = $this->getRelativePath();

        $parts = array_filter(explode(DIRECTORY_SEPARATOR, $directory));

        if (count($parts) === 0) {
            return '*';
        }

        return collect($parts)->map(function ($part) {
            return Str::slug($part);
        })->implode('.');
    }

    /**
     * Return the name of the parent directory
     *
     * @return string
     */
    public function getParent(): string
    {
        return basename(
            $this->getRelativePath()
        );
    }

    /**
     * Returns an array of properties after scanning the component documentation comment block
     *
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function parseDocumentationComment(): array
    {
        $source = $this->getSourceWithComments();
        $pattern = $this->getBladeCommentsRegex();
        $matches = [];

        preg_match($pattern, $source, $matches);

        if (!isset($matches[1])) {
            return [];
        }

        $documentation = YamlFrontMatter::parse($matches[1]);

        return [
            'attributes' => Arr::except($documentation->matter(), ['slots', 'variables']),
            'slots'      => Arr::collapse($documentation->matter('slots', [])),
            'variables'  => Arr::collapse($documentation->matter('variables', [])),
            'text'       => $documentation->body(),
        ];
    }

    /**
     * Returns custom attributes set by the user
     *
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getAttributes(): array
    {
        return data_get($this->parseDocumentationComment(), 'attributes', []);
    }

    /**
     * Returns the available slots and their values
     *
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getSlots(): array
    {
        return data_get($this->parseDocumentationComment(), 'slots', []);
    }

    /**
     * Returns variables that can be passed through to the component
     *
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getVariables(): array
    {
        return data_get($this->parseDocumentationComment(), 'variables', []);
    }

    /**
     * Returns the contents of the documentation block outside of the front-matter
     *
     * @return string|null
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getText(): ?string
    {
        return data_get($this->parseDocumentationComment(), 'text', null);
    }

    /**
     * Converts the component model into a simple array
     *
     * @return array
     */
    public function toArray(): array
    {
        return collect($this->visibleAttributes)->mapWithKeys(function ($property) {

            return [Str::snake($property) => $this->{'get' . Str::studly($property)}()];

        })->toArray();
    }

    /**
     * Returns a JSON serialized version of the component model
     *
     * @param int $options
     *
     * @return false|string
     */
    public function toJson($options = 0)
    {
        $json = json_encode($this->jsonSerialize(), $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new DomainException(json_last_error_msg());
        }

        return $json;
    }

    /**
     * Outputs the data that can be serialized to JSON
     *
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Magic helper to dynamically retrieve properties
     *
     * @param $key
     *
     * @return void
     */
    public function __get($key)
    {
        if (!$key) {
            return;
        }

        $method = 'get' . Str::studly($key);

        if (!method_exists($this, $method)) {
            return;
        }

        return $this->{$method}();
    }

    /**
     * Magic helper to dynamically check existence of properties
     *
     * @param $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        $method = 'get' . Str::studly($key);

        return method_exists($this, $method);
    }

    /**
     * Convert the model to a string value
     *
     * @return false|string
     */
    public function __toString()
    {
        return $this->toJson();
    }
}

