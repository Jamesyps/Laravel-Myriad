<?php

namespace Jamesyps\Myriad\Contracts;

interface ComponentRepositoryInterface
{
    /**
     * Returns a list of all found components
     *
     * @return array
     */
    public function all(): array;

    /**
     * Returns a grouped list of components, filterable by namespace
     *
     * @param string|null $namespace
     *
     * @return array
     */
    public function grouped(?string $namespace = null): array;

    /**
     * Return hierarchical list of component namespaces
     *
     * @return array
     */
    public function namespaces(): array;

    /**
     * Find a single component instance by its key
     *
     * @param string $key
     *
     * @return \Jamesyps\Myriad\Contracts\ComponentInterface|null
     */
    public function findByKey(string $key): ?ComponentInterface;
}
