<?php

namespace Jamesyps\Myriad\Tests;

use Illuminate\Filesystem\Filesystem;
use Jamesyps\Myriad\Contracts\ComponentInterface;
use Jamesyps\Myriad\Contracts\ComponentRepositoryInterface;

class ComponentRepositoryTest extends MyriadTestCase
{
    /**
     * @var ComponentRepositoryInterface
     */
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(ComponentRepositoryInterface::class);
    }

    public function testComponentsCanBeFound()
    {
        $result = $this->repository->all();
        $files = $this->app->make(Filesystem::class)->allFiles($this->getViewPath());

        $this->assertIsArray($result);
        $this->assertCount(count($files), $result);
    }

    public function testComponentsCanGroupByNamespace()
    {
        $result = $this->repository->grouped();

        $this->assertArrayHasKey('*', $result);
        $this->assertArrayHasKey('nested', $result);
        $this->assertArrayHasKey('nested.deep-nesting', $result);
    }

    public function testComponentsCanBeFilteredByNamespace()
    {
        $result = $this->repository->grouped('nested');

        $this->assertArrayNotHasKey('*', $result);
    }

    public function testComponentNamespaceIncludesChildren()
    {
        $result = $this->repository->grouped('nested');

        $this->assertArrayHasKey('nested', $result);
        $this->assertArrayHasKey('nested.deep-nesting', $result);
    }

    public function testComponentNamespaceHierarchy()
    {
        $result = $this->repository->namespaces();

        $structure = [
            '*'      => [
                'key' => '*',
            ],
            'nested' => [
                'key'      => 'nested',
                'children' => [
                    'deep-nesting' => [
                        'key' => 'nested.deep-nesting',
                    ],
                ],
            ],
        ];

        $this->assertIsArray($result);
        $this->assertEquals($structure, $result);
    }

    public function testComponentCanBeFoundByKey()
    {
        $keys = [
            'button',
            'nested.quote',
            'nested.deep-nesting.panel',
        ];

        foreach ($keys as $key) {
            $result = $this->repository->findByKey($key);

            $this->assertInstanceOf(ComponentInterface::class, $result, $key);
            $this->assertEquals($result->key, $key);
        }
    }

    public function testUnknownComponentFailsSafely()
    {
        $result = $this->repository->findByKey(md5(rand()));

        $this->assertNull($result);
    }
}
