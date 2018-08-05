<?php

namespace App\Modules\Core\Providers;

//use App\Modules\Permissions\Models\Permission;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(Gate $gate)
    {
        $this->registerPolicies();
        $gate->before(function($user) use ($gate){
            // If user is super admin break all gates
            if($user->hasRole('Administrator')){
                return true;
            }
            // User/Role-based permissions
            $permissions = $this->getPermissions($user);

            foreach ($permissions as $permission)
            {
                $gate->define($permission, function ($user) use ($permission)
                {
                    return ($user->hasPermission($permission));
                });
            }

        });
        
    }

    protected function getPermissions($user)
    {
       return array_keys(all_permissions());
    }
}
