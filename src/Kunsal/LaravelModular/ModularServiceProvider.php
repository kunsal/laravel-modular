<?php
/**
 * Created by PhpStorm.
 * User: Olakunle.Salami
 * Date: 5/12/2018
 * Time: 6:30 PM
 */

namespace Kunsal\LaravelModular;


use Illuminate\Support\ServiceProvider;

class ModularServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Get files and folders in modules directory
        $modules = $this->scanModulesPath();
        // Loop through modules files and directories to load specific files automatically
        foreach ($modules as $module) {
            // ignore if not file or folder
            if($module == '.' || $module == '..'){
                continue;
            }
            // Load routes file if it exists
            if(is_dir($this->modulesPath().'/'.$module.'/Http')){
                $this->loadRoutesFrom($this->modulesPath().'/'.$module.'/Http/routes.php');
            }
            // Load view file if it exists
            if(is_dir($this->modulesPath().'/'.$module.'/Resources/Views')){
                $this->loadViewsFrom($this->modulesPath().'/'.$module.'/Resources/Views', $module);
            }
            // Load translation file if it exists
            if(is_dir($this->modulesPath().'/'.$module.'/Resources/Lang')){
                $this->loadTranslationsFrom($this->modulesPath().'/'.$module.'/Resources/Lang', $module);
            }
        }
    }

    public function register()
    {
        // Get files and folders in modules directory
        $modules = $this->scanModulesPath();
        // Loop through modules and register service providers by naming convention {anything}Provider.php
        foreach ($modules as $module) {
            if($module == '.' || $module == '..'){
                continue;
            }
            // Scan through the module's providers folder for files
            $providers = scandir($this->modulesPath().'/'.$module.'/Providers');
            // Loop through provider files and register them to the service container
            foreach($providers as $provider){
                if($provider == '.' || $provider == '..' || $provider == class_basename($this).'.php'){
                    continue;
                }
                // Remove the .php extension from provider class name
                $provider_class = explode('.p', $provider)[0];
                $this->app->register('App\\Modules\\'.$module.'\\Providers\\'.$provider_class);
            }
        }
    }


    /**
     * Get the modules path
     * @return string
     */
    private function modulesPath()
    {
        return app_path('Modules');
    }

    private function scanModulesPath()
    {
        return scandir($this->modulesPath());
    }
}