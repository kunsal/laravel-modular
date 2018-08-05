<?php

namespace Kunsal\LaravelModular\Core\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class GenerateModule extends Command
{
    use ReplaceStubTraits;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {name} {--schema=} {--empty} {--type=} {--form=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates application module';
    
    protected $files;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $file)
    {
        parent::__construct();
        $this->files = $file;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Retrieve arguments and options
        $module = $this->argument('name');
        $schema = $this->option('schema');
        $empty = $this->option('empty');
        $type = $this->option('type');
        $form = $this->option('form');

        $module_plural = str_plural($module);
        // Initial variables
        $module_path = 'app/Modules/'.$module_plural;
        $namespace = "App\\Modules\\{$module_plural}";

        if(file_exists($module_path) && is_dir($module_path)){
            $this->error($module. ' Module already exists');
            exit;
        }
        //$stub = $this->files->get($this->getStub());
        //$this->info($this->replaceNamespace($stub, $module)->replaceClass($stub, $module));
        $this->makeController($module, $module_path, $namespace, $empty, $type);
        $this->makeModel($module, $module_path, $namespace, $schema, $form);
        $this->makePresenter($module, $module_path, $namespace);
        $this->makeProvider($module, $module_path, $namespace);
        $this->makeEventProvider($module, $module_path, $namespace);
        $this->makeResources($module_path, $module);
        $this->empty_folders($module_path);
        $this->info("{$module} module created successfully");
    }

    // Get stub file
    protected function moduleStub($filename){
        return __DIR__ . "/stubs/{$filename}.stub";
    }
    
    protected function makeController($module, $module_path,$namespace, $empty=false, $type='')
    {
        // The stub file
        if($empty == true){
            $stub_path = $this->moduleStub('controllers');
        }else{
            $stub_path = $this->moduleStub('resource-controllers');
        }

        // Data to replace in stub
        $plural = str_plural($module);
        $lowerplural = strtolower($plural);
        $class =  $plural."Controller";
        $name = ucfirst($module);
        $namespace = "namespace {$namespace}\\Http\\Controllers";
        $lowername = strtolower($module);
        // Path to directory to create file in
        $controller_path = "{$module_path}/Http/Controllers";
        // Create directory with read, write, execute
        mkdir($controller_path, 0777, true);
        $stub = $this->files->get($stub_path);
        $stub = $this->replaceClass($stub, $class); 
        $stub = $this->replaceNamespace($stub, $namespace);
        $stub = $this->replaceName($stub, $name);
        $stub = $this->replaceLowerName($stub, $lowername);
        $stub = $this->replacePlural($stub, $plural);
        $stub = $this->replaceType($stub, $type);
        $stub = $this->replaceLowerPlural($stub, $lowerplural);

        $this->files->put("{$controller_path}/{$class}.php", $stub);
        
        file_put_contents("{$module_path}/Http/routes.php", ($empty == false) ? $this->resource_route($plural) : $this->route($plural));
        $this->makeRequests($module, $module_path, $namespace);
    }

    protected function makeModel($module, $module_path,$namespace, $schema=null, $form=null)
    {
        $plural = str_plural($module);
        $class = ucfirst($module);
        $name_space = $namespace;
        $namespace = "namespace {$name_space}\\Models";
        $model_path = "{$module_path}/Models";
        mkdir($model_path, 0777, true);
        // The stub file
        $stub_path = $this->moduleStub('model');

        $stub = $this->files->get($stub_path);
        $stub = $this->replaceClass($stub, $class);
        $stub = $this->replaceNamespace($stub, $namespace);
        $stub = $this->replacePlural($stub, $plural);
        // If user is being generated
        if(strtolower($module) == 'user'){
            $stub = str_replace('extends Model', 'extends Authenticable', $stub);
            $stub = str_replace('Illuminate\Database\Eloquent\Model;', 'Illuminate\Foundation\Auth\User as Authenticable;', $stub);
        }

        $this->files->put("{$model_path}/{$class}.php", $stub);
        
        if(!is_null($schema)){
            $this->call('make:migration:schema', ['name' => 'create_'.strtolower($plural).'_table', '--schema' => $schema, '--model'=>0]);
        }

        if(!is_null($form)){
            $this->call('make:form', ['name' => $name_space.'\\Forms\\'.$class.'Form', '--fields' => $form]);
        }else{
            $this->call('make:form', ['name' => $name_space.'\\Forms\\'.$class.'Form']);
        }

    }

    protected function makePresenter($module, $module_path,$namespace)
    {
        $class = ucfirst($module)."Presenter";
        $namespace = $namespace.'\\Presenters';
        $stub_path = $this->moduleStub('presenter');

        $stub = $this->files->get($stub_path);
        $stub = $this->replaceClass($stub, $class);
        $stub = $this->replaceNamespace($stub, $namespace);
        
        $presenter_path = "{$module_path}/Presenters";
        mkdir($presenter_path, 0777, true);
        file_put_contents("{$presenter_path}/{$module}Presenter.php", $stub);
    }
    
    protected function makeProvider($module, $module_path,$namespace)
    {
        $plural = str_plural($module);
        $namespace = $namespace.'\\Providers';
        $name = ucfirst($module);
        
        $stub_path = $this->moduleStub('provider');

        $stub = $this->files->get($stub_path);

        $stub = $this->replaceNamespace($stub, $namespace);
        $stub = $this->replaceName($stub, $name);
        $stub = $this->replacePlural($stub, $plural);

        $provider_path = "{$module_path}/Providers";
        mkdir($provider_path, 0777, true);
        file_put_contents("{$provider_path}/{$plural}ServiceProvider.php", $stub);
    }

    protected function makeEventProvider($module, $module_path,$namespace)
    {
        $plural = str_plural($module);
        $namespace = $namespace.'\\Providers';
        $name = ucfirst($module);

        $stub_path = $this->moduleStub('event-provider');

        $stub = $this->files->get($stub_path);

        $stub = $this->replaceNamespace($stub, $namespace);
        $stub = $this->replaceName($stub, $name);
        $stub = $this->replacePlural($stub, $plural);

        $provider_path = "{$module_path}/Providers";
        //mkdir($provider_path, 0777, true);
        file_put_contents("{$provider_path}/{$plural}EventServiceProvider.php", $stub);
    }

    public function empty_folders($module_path)
    {
        $folders = array(
            'Listeners', 'Traits', 'Events', 'Mail'
        );

        foreach($folders as $folder){
            $model_path = "{$module_path}/$folder";
            mkdir($model_path, 0777, true);
        }
    }

    protected function makeResources($module_path, $module)
    {
        $module_path = "{$module_path}/Resources";
        $lower_plural = strtolower(str_plural($module));
        
        $view_path = $module_path.'/Views';
        $lang_path = $module_path.'/Lang/en';
        mkdir($view_path, 0777, true);
        mkdir($lang_path, 0777, true);

        $stub_path = $this->moduleStub('view');
        $form_stub_path = $this->moduleStub('form');
        // Index page stub
        $stub = $this->files->get($stub_path);
        $stub = $this->replaceLowerPlural($stub, $lower_plural);
        $stub = $this->replaceName($stub, $module);
        // Create and edit stub
        $form_stub = $this->files->get($form_stub_path);
        $form_stub = $this->replaceName($form_stub, $module);
        $form_stub = $this->replaceLowerPlural($form_stub, $lower_plural);
        
        file_put_contents("{$view_path}/index.blade.php", $stub);
        file_put_contents("{$view_path}/form.blade.php", $form_stub);
    }

    protected function makeRequests($module, $module_path,$namespace)
    {
        $namespace = 'App\Modules\\'.str_plural(ucfirst($module)).'\Http\Requests';
        $request_path = "{$module_path}/Http/Requests";
        mkdir($request_path, 0777, true);

        foreach(range(1,2) as $number){
            $class = '';
            switch($number){
                case 1:
                    $class = "Store{$module}Request";
                    break;
                case 2:
                    $class = "Update{$module}Request";
                    break;
            }
            $stub_path = $this->moduleStub('requests');

            $stub = $this->files->get($stub_path);

            $stub = $this->replaceClass($stub, $class);
            $stub = $this->replaceNamespace($stub, $namespace);
            
            file_put_contents("{$request_path}/{$class}.php", $stub);
        }
    }

    private function resource_route($plural)
    {
        $module = strtolower($plural);
        $stub_path = $this->moduleStub('resource-route');

        $stub = $this->files->get($stub_path);

        $stub = $this->replaceName($stub, $module);
        $stub = $this->replacePlural($stub, $plural);

        return $stub;
    }

    private function route($plural)
    {
        $namespace = 'App\Modules\\'.ucfirst($plural).'\Http\Controllers';
        $stub_path = $this->moduleStub('route');

        $stub = $this->files->get($stub_path);

        $stub = $this->replaceNamespace($stub, $namespace);

        return $stub;
    }

}
