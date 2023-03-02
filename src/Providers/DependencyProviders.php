<?php


namespace Sourcebit\Dprimecms\Providers;


use Illuminate\Pagination\Paginator;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Facade;
use \Sourcebit\Dprimecms\Http\Middleware\AdminMiddleware;
use Sourcebit\Dprimecms\Http\Middleware\AuthxMiddleware;
use Sourcebit\Dprimecms\Http\Middleware\UserMiddleware;
use Illuminate\Foundation\AliasLoader;
use Sourcebit\Dprimecms\Console\InstallDprimeCmsPackage;

class DependencyProviders extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

        $this->registerAliases();
    }

    /**
     * Bootstrap services.
     */
    public function boot(\Illuminate\Routing\Router $router): void
    {

        $this->app->alias('Content', 'Sourcebit\Dprimecms\Facades\Content');
        $this->app->alias('Auth', 'Sourcebit\Dprimecms\Facades\Auth');

        Paginator::useBootstrap();

        $this->app->bind(
            'Sourcebit\Dprimecms\Repositories\AuthInterface',
            'Sourcebit\Dprimecms\Repositories\AuthRepository'
        );

        $router->middlewareGroup('admin', [AdminMiddleware::class]);
        $router->middlewareGroup('authx', [AuthxMiddleware::class]);
        $router->middlewareGroup('user', [UserMiddleware::class]);

        $this->app->register(RouteServiceProvider::class);

        // helper Add
        require_once __DIR__.'/../Helpers/app_helper.php';
        require_once __DIR__.'/../Helpers/helper.php';
        require_once __DIR__.'/../Helpers/theme_helper.php';

        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'sourcebit');

        // Publish config
        $config = realpath(__DIR__.'/../config/config.php');

        $this->mergeConfigFrom($config, 'sourcebit.config');

        $this->publishes([
            $config => config_path('sourcebit.config.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../../public/backend' => public_path('backend'),
        ], 'public');

//        $this->mergeConfigFrom(__DIR__.'/../config/cartalyst.sentinel.php', 'sentinel');
        $this->registerAliases();

        $this->installPackage()
    }


    protected function registerAliases()
    {
        $aliases = [
            //auth
            'Auth' => Sourcebit\Dprimecms\Facades\Auth::class,
            'Content' => Sourcebit\Dprimecms\Facades\Content::class,
        ];

        foreach ($aliases as $alias => $class) {
            $loader = AliasLoader::getInstance();
            $loader->alias($class, $alias);
        }
    }

    public function installPackage(){
        if ($this->app->runningInConsole()) {
            // publish config file

            $this->commands([
                InstallDprimeCmsPackage::class,
            ]);
        }
    }
}
