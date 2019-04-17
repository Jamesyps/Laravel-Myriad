<?php

namespace Jamesyps\Myriad;

use Carbon\Laravel\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Jamesyps\Myriad\Models\Component;
use Jamesyps\Myriad\Contracts\ComponentInterface;
use Jamesyps\Myriad\Facades\Myriad as MyriadFacade;
use Jamesyps\Myriad\Repositories\ComponentRepository;
use Jamesyps\Myriad\Contracts\ComponentRepositoryInterface;

class MyriadServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
        $this->registerBindings();

        if ($this->app->runningInConsole()) {
            $this->registerPublishable();
        }

        $this->app->alias('Myriad', MyriadFacade::class);

        $this->app->singleton('Myriad', function () {
            return new Myriad();
        });
    }

    /**
     * Register configuration file to container
     *
     * @return void
     */
    private function registerConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/myriad.php', 'myriad');
    }

    /**
     * Register contract bindings
     *
     * @return void
     */
    private function registerBindings()
    {
        $this->app->bind(ComponentInterface::class, config('myriad.model', Component::class));
        $this->app->bind(ComponentRepositoryInterface::class, config('myriad.repository', ComponentRepository::class));
    }

    private function registerPublishable()
    {
        $publishable = [
            'myriad-config' => [
                __DIR__ . '/../config/myriad.php' => config_path('myriad.php'),
            ],
            'myriad-views'  => [
                __DIR__ . '/../resources/views' => resource_path('views/vendor/myriad'),
            ],
            'myriad-assets' => [
                __DIR__ . '/../assets' => public_path('myriad'),
            ],
        ];

        foreach ($publishable as $group => $paths) {
            $this->publishes($paths, $group);
        }
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'myriad');
        $this->loadViewsFrom(config('myriad.directory'), 'myriad-components');

        Route::group($this->routesConfig(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/myriad.php');
        });
    }

    private function routesConfig(): array
    {
        return [
            'prefix'     => config('myriad.route'),
            'namespace'  => 'Jamesyps\Myriad\Http\Controllers',
            'as'         => 'myriad.',
            'middleware' => 'web',
        ];
    }
}
