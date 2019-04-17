<?php

namespace Jamesyps\Myriad\Contracts;

interface ComponentInterface
{

    /**
     * Humanised name of the blade file
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Returns the unique key based on the component path
     *
     * @return string
     */
    public function getKey(): string;

    /**
     * Returns the view path that can be used to within blade include / component directives
     *
     * @return string
     */
    public function getViewPath(): string;

    /**
     * Returns the raw source code of the blade file
     *
     * @param bool $comments
     *
     * @return string
     */
    public function getSource(bool $comments = false): string;

    /**
     * Returns the raw source code without removing comments
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getSourceWithComments(): string;

    /**
     * Returns the namespace (the parent directory path)
     *
     * @return string
     */
    public function getNamespace(): string;

    /**
     * Return the name of the parent directory
     *
     * @return string
     */
    public function getParent(): string;

    /**
     * Returns custom attributes set by the user
     *
     * @return array
     */
    public function getAttributes(): array;

    /**
     * Returns the available slots and their values
     *
     * @return array
     */
    public function getSlots(): array;

    /**
     * Returns variables that can be passed through to the component
     *
     * @return array
     */
    public function getVariables(): array;

    /**
     * Returns the contents of the documentation block outside of the front-matter
     *
     * @return string|null
     */
    public function getText(): ?string;

}
