<?php

namespace Jamesyps\Myriad\Repositories;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Jamesyps\Myriad\Contracts\ComponentInterface;
use Jamesyps\Myriad\Contracts\ComponentRepositoryInterface;

class ComponentRepository implements ComponentRepositoryInterface
{
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $componentsPath;

    /**
     * @var \Illuminate\Support\Collection
     */
    private $components;

    /**
     * ComponentRepository constructor.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->componentsPath = config('myriad.directory', resource_path('views/components'));

        $this->scanForComponents();
    }

    /**
     * Returns a list of all found components
     *
     * @return array
     */
    public function all(): array
    {
        return $this->components->toArray();
    }

    /**
     * Returns a grouped list of components, filterable by namespace
     *
     * @param string|null $namespace
     *
     * @return array
     */
    public function grouped(?string $namespace = null): array
    {
        $hasNamespace = !(is_null($namespace) || empty($namespace));

        return $this->components
            ->when($hasNamespace, function ($components) use ($namespace) {
                return $components->filter(function ($component) use ($namespace) {
                    return Str::startsWith($component->namespace, $namespace);
                });
            })
            ->groupBy('namespace')
            ->sortKeys()
            ->toArray();
    }

    /**
     * Return hierarchical list of component namespaces
     *
     * @return array
     */
    public function namespaces(): array
    {
        $namespaces = [];

        $this->components->sortBy('namespace')->each(function ($component) use (&$namespaces) {

            $key = str_replace('.', '.children.', $component->namespace);

            Arr::set($namespaces, $key . '.key', $component->namespace);
        });

        return $namespaces;
    }

    /**
     * Find a single component instance by its key
     *
     * @param string $key
     *
     * @return \Jamesyps\Myriad\Contracts\ComponentInterface|null
     */
    public function findByKey(string $key): ?ComponentInterface
    {
        return $this->components->firstWhere('key', $key);
    }

    /**
     * Scans directory for component blade files
     *
     * @return void
     */
    private function scanForComponents(): void
    {
        if (!$this->components || $this->components->isEmpty()) {
            $paths = collect($this->filesystem->allFiles($this->componentsPath));

            $this->components = $paths->filter(function ($path) {

                return Str::endsWith($path, '.blade.php');
            })->map(function ($path) {

                return app()->makeWith(ComponentInterface::class, [
                    'fullPathToView' => $path,
                ]);
            });
        }
    }
}
