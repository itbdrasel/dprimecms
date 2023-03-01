<?php

namespace Sourcebit\Dprimecms\Providers;

use Illuminate\Database\Eloquent\Builder;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class DprimcmsProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {


    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {


        //application settings assign to configuration.
        config()->set('settings', \Sourcebit\Dprimecms\Models\Settings::pluck('s_value', 's_name')->all());

        //app settings assign to views as global
        view()->share(config('settings'));

        //more global vars for views
        view()->share([
            'version'	=> 'Version 1.0',
        ]);


        Builder::macro('whereLike', function(string $attribute, string $searchTerm) {
            return $this->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
        });


        Builder::macro('joinLeft', function(string $foreignTable, string $table, string $foreignKey) {
            return $this->leftJoin($foreignTable, $table.'.'.$foreignKey,'=',$foreignTable.'.'.$foreignKey);
        });
        Builder::macro('orderByFilter', function(string $model, string $id) {
            return $this->orderBy(getOrder($model::$sortable, $id)['by'], getOrder($model::$sortable, $id)['order']);
        });


        View::composer(['frontend.*','errors.*'], \Sourcebit\Dprimecms\ViewComposers\DefaultComposer::class);

        $this->app->register(DependencyProviders::class);

    }
}
