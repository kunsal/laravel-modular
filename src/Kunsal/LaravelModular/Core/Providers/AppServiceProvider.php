<?php

namespace App\Modules\Core\Providers;

use App\Modules\Core\View\ThemeViewFinder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Increase database string length
        Schema::defaultStringLength(191);
        
        // override default view finder
        $this->app['view']->setFinder($this->app['theme.finder']);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('theme.finder', function($app){
            $finder = new ThemeViewFinder($app['files'], $app['config']['view.paths']);
            $config = $app['config']['cms.theme'];
            $finder->setBasePath($app['path.public'].'/'.$config['folder']);
            $finder->setActiveTheme($config['active']);

            return $finder;
        });
    }
}
