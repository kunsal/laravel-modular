<?php
/**
 * Created by PhpStorm.
 * User: Olakunle.Salami
 * Date: 5/12/2018
 * Time: 6:30 PM
 */

namespace Kunsal\LaravelModular;


use Illuminate\Support\ServiceProvider;
use Kunsal\LaravelModular\Core\Commands\GenerateModule;

class ModularServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands([GenerateModule::class]);
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
            if(file_exists($this->modulesPath().'/'.$module.'/Providers')){
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
    }


    /**
     * Get the modules path
     * @return string
     */
    private function modulesPath()
    {
        $module_path = app_path('Modules');
        if(file_exists($module_path)){
            // Create core module if not already created
            if(!file_exists($module_path.'/Core')){
                $this->createCoreModule($module_path);
            }
        }else{
            mkdir($module_path);
            // Create core module
            $this->createCoreModule($module_path);

        }
        return $module_path;
    }

    private function scanModulesPath()
    {
        return scandir($this->modulesPath());
    }

    protected function createCoreModule($module_path)
    {
        $core_path = $module_path.'/Core';
        mkdir($core_path);
        $directories = ['Composers','Config','Facades','Helper','Http','Libraries','Models', 'Providers', 'Resources'];
        $this->makeSubFolders($directories, $core_path);
    }

    protected function makeSubFolders($directories, $core_path)
    {
        foreach($directories as $directory){
            $dir_path = $core_path.'/'.$directory;
            mkdir($dir_path);
            if(value($directory) == 'Http'){
                $dirs = ['Controllers', 'Middlewares'];
                $this->makeSubFolders($dirs, $dir_path);
                file_put_contents($dir_path.'/routes.php', '<?php');
            }
            if(value($directory) == 'Resources'){
                $dirs = ['Views', 'Lang'];
                $this->makeSubFolders($dirs, $dir_path);
            }
        }
    }
}