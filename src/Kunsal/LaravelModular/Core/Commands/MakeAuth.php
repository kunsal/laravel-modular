<?php
/**
 * Created by PhpStorm.
 * User: Olakunle.Salami
 * Date: 06/08/2018
 * Time: 10:42 AM
 */

namespace Kunsal\LaravelModular\Core\Commands;


use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeAuth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module:auth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates application module authentication system';

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
    public function handle(){
        // Retrieve arguments and options
        $module = 'Authenticator';
        if($this->confirm('Generating this module will delete app/Http/Controllers folder. Do you want to continue?')){
            //$this->call('make:module', ['name' => $module]);
            $module_path = 'app/Modules/Authenticators';
            if($this->files->isDirectory($module_path)){
                $this->makeLoginController($module_path);
                $this->makeAuthRoutes($module_path);
                $this->makeForgotPasswordController($module_path);
                $this->makeResetPasswordController($module_path);
                // Delete existing auth
                if($this->files->isDirectory(app_path('Http/Controllers'))){
                    $this->files->deleteDirectory(app_path('Http/Controllers'));
                }
                $this->info('Authentication module generated');
            }
        }else{
            $this->info('You declined generating authentication module');
        }
    }

    // Get stub file
    protected function moduleStub($filename){
        return __DIR__ . "/stubs/auth/{$filename}.stub";
    }

    public function makeLoginController($module_path)
    {
        $stub_path = $this->moduleStub('login-controller');
        $stub = $this->files->get($stub_path);
        $this->files->put("{$module_path}/Http/Controllers/LoginController.php", $stub);
    }

    public function makeForgotPasswordController($module_path)
    {
        $stub_path = $this->moduleStub('forgot-password-controller');
        $stub = $this->files->get($stub_path);
        $this->files->put("{$module_path}/Http/Controllers/ForgotPasswordController.php", $stub);
    }

    public function makeResetPasswordController($module_path)
    {
        $stub_path = $this->moduleStub('reset-password-controller');
        $stub = $this->files->get($stub_path);
        $this->files->put("{$module_path}/Http/Controllers/ResetPasswordController.php", $stub);
    }

    public function makeAuthRoutes($module_path)
    {
        $stub_path = $this->moduleStub('auth-routes');
        $stub = $this->files->get($stub_path);
        $this->files->put("{$module_path}/Http/routes.php", $stub);
    }
}