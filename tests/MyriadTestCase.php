<?php

namespace Jamesyps\Myriad\Tests;

use Jamesyps\Myriad\Contracts\ComponentInterface;
use Jamesyps\Myriad\Facades\Myriad;
use Jamesyps\Myriad\MyriadServiceProvider;
use Orchestra\Testbench\TestCase;

abstract class MyriadTestCase extends TestCase
{
    protected $viewPath = __DIR__ . '/views/components';

    protected function getPackageProviders($app)
    {
        return [
            MyriadServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Myriad' => Myriad::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('view.paths', [
            __DIR__ . '/views'
        ]);

        $app['config']->set('myriad.directory', $this->viewPath);
    }

    protected function getComponent(string $view): ComponentInterface
    {
        return $this->app->makeWith(ComponentInterface::class, [
            'fullPathToView' => $this->getViewPath($view),
        ]);
    }

    protected function getViewPath(string $view = ''): string
    {
        return $this->viewPath . ($view ? DIRECTORY_SEPARATOR . ltrim($view, '/') : $view);
    }
}
