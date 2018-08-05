<?php

namespace Kunsal\LaravelModular\Core\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallApp extends Command
{
    use ReplaceStubTraits;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:app {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets up a fresh application';
    
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
    public function fire()
    {
        // Retrieve arguments and options
        $app_name = $this->argument('name');
        
        $this->makeSettings($app_name);
        $this->makeRoles();
        $this->makeDefaultUser();
        // Run pending migrations
        $this->call('migrate');
        // Save all optimized autoload files
        exec('Composer dump-autoload');
        // Save roles to database
        $this->call('db:seed', ['--class' => 'RolesTableSeeder']);
        // Save default user to database
        $this->call('db:seed', ['--class' => 'UsersTableSeeder']);
        // Save settings to database
        $this->call('db:seed', ['--class' => 'SettingsTableSeeder']);
        // Finished installation
        $this->info("{$app_name} installed successfully");
    }

    // Get stub file
    protected function moduleStub($filename){
        return __DIR__ . "/stubs/{$filename}.stub";
    }

    // Generate settings migration and seeder
    protected function makeSettings($app_name){
        $stub = $this->files->get($this->moduleStub('settings_migration'));
        $filename = base_path('database/migrations/2017_06_29_055743_create_settings_table.php');
        if(!$this->files->exists($filename)){
            $this->files->put($filename, $stub);
            $this->info('Settings migration created');
        }
        // Create seeder for roles
        $seeder_stub = $this->files->get($this->moduleStub('settings-table-seeder'));
        $seeder_stub = $this->replaceName($seeder_stub, $app_name);
        $seeder_file = base_path('database/seeds/SettingsTableSeeder.php');
        if(!$this->files->exists($seeder_file)){
            $this->files->put($seeder_file, $seeder_stub);
            $this->info('Settings seeder created');
        }
        
        $this->call('make:module', ['name' => 'Setting', '--form' => 'app_name:text, tagline:text, 
        app_description:textarea, email_address:text, phone:text, address:text, facebook:text, twitter:text, 
        google_plus:text, youtube:text, instagram:text, whatsapp:text, alt_email:text, alt_phone:text, logo:file']);
        
    }

    protected function makeRoles()
    {
        // Add additional field(s) to roles table
        $stub = $this->files->get($this->moduleStub('roles-migration'));
        $filename = base_path('database/migrations/2017_06_29_082408_add_can_edit_to_roles_table.php');
        if(!$this->files->exists($filename)){
            $this->files->put($filename, $stub);
            $this->info('Role migration created');
        }
        // Create seeder for roles
        $seeder_stub = $this->files->get($this->moduleStub('roles-table-seeder'));
        $seeder_file = base_path('database/seeds/RolesTableSeeder.php');
        if(!$this->files->exists($seeder_file)){
            $this->files->put($seeder_file, $seeder_stub);
            $this->info('Role seeder created');
        }
        $this->call('make:module', ['name' => 'Role', '--form' => 'name:text, permission:select']);
    }

    protected function makeDefaultUser()
    {
        // Create seeder for users
        $seeder_stub = $this->files->get($this->moduleStub('users-table-seeder'));
        $seeder_file = base_path('database/seeds/UsersTableSeeder.php');
        if(!$this->files->exists($seeder_file)){
            $this->files->put($seeder_file, $seeder_stub);
            $this->info('User seeder created');
        }
        $this->call('make:module', ['name' => 'User', '--form' => 'first_name:text, last_name:text, email:text, role:select']);
    }
    

}
