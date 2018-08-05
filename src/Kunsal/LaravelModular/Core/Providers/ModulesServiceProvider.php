<?php

namespace App\Modules\Core\Providers;

use App\Modules\Core\Composers\AddStatusMessage;
use App\Modules\Core\Composers\AddLoggedInUser;
use App\Modules\Core\Composers\AddSuccessAndErrorMessage;
use App\Modules\Core\Composers\AttachGlobalSettings;
use App\Modules\Core\Libraries\TripleDES;
use App\Modules\Pages\Composers\AttachPages;
use Illuminate\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider{

    /**
     * Will make sure that the required modules have been fully loaded
     * @return void
     */
    public function boot(){
        config(['app.timezone' => 'Africa/Lagos']);
       // $modules = config('modules.modules');
        $modules_path = app_path('Modules');
        // Publish config file
        $this->publishes([
            $modules_path.'/Core/Config/navigations.php' => config_path('navigations.php'),
        ]);
        $modules = scandir($modules_path);

        foreach($modules as $module){
            if($module == '..') continue;
            // Autoload routes
            if(is_dir($modules_path.'/'.$module.'/Http')){
                $this->loadRoutesFrom($modules_path.'/'.$module.'/Http/routes.php');
            }
            // Autoload views
            if(is_dir($modules_path.'/'.$module.'/Resources/Views')){
                $this->loadViewsFrom($modules_path.'/'.$module.'/Resources/Views', $module);
            }
            // Autoload Translations
            if(is_dir($modules_path.'/'.$module.'/Resources/Lang')){
                $this->loadTranslationsFrom($modules_path.'/'.$module.'/Resources/Lang', $module);
            }

        }
        $this->loadViewsFrom(app_path('views/vendor/laravel-form-builder'), 'laravel-form-builder');
        view()->composer(['layouts.auth', 'layouts.backend','layouts.master_email', 'layouts._partials.notify', 'layouts._partials.notify-success', 'layout.member'], AddStatusMessage::class);
        $this->app['view']->composer(['layouts._partials.custom-notify'], AddSuccessAndErrorMessage::class);
        $this->app['view']->composer(['layouts.backend', 'layouts.member'], AddLoggedInUser::class);
        $this->app['view']->composer(['layouts.frontend', '404'], AttachPages::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $modules_path = app_path('Modules');
        $this->mergeConfigFrom(
            $modules_path.'/Core/Config/navigations.php', 'navigations'
        );

        $this->app->bind('triple_des', function () {
            return new TripleDES();
        });

        if ($this->app->environment() == 'local') {
            $this->app->register('Laracasts\Generators\GeneratorsServiceProvider');
        }

        $modules = scandir($modules_path);
        // Register ServiceProviders ... naming must be ModulenameServiceProvider
        foreach($modules as $module)
        {
            if (file_exists($modules_path . '/' . $module . '/Providers/'.$module.'ServiceProvider.php'))
            {
                $this->app->register('App\\Modules\\' . $module . '\\Providers\\'.$module.'ServiceProvider');
            }
            if (file_exists($modules_path . '/' . $module . '/Providers/'.$module.'EventServiceProvider.php'))
            {
                $this->app->register('App\\Modules\\' . $module . '\\Providers\\'.$module.'EventServiceProvider');
            }
        }

    }

}