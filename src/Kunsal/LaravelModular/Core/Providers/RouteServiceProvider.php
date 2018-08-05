<?php

namespace App\Modules\Core\Providers;

use App\Modules\Pages\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));

        try{
            // Handle route for different pages
            foreach (Page::all() as $page)
            {
                Route::get($page->uri, ['as' => $page->slug, function(Router $router) use ($page){

                    return $this->app->call('App\Modules\Pages\Http\Controllers\FrontPagesController@show', [
                        'page' => $page,
                        'parameters' => $router->current()->parameters()
                    ]);
                }])->middleware('web');
            }

            /*Route::middleware('web')->group(function(){
                // Handles frontal post categories
                Route::get('c/{cat}', 'App\Modules\Categories\Http\Controllers\FrontCategoriesController@index')->name('public.post.category');

                // Handles frontend related posts
                Route::get('{type}/{slug}', 'App\Modules\Posts\Http\Controllers\PublicPostsController@index')->name('single.post');

            });*/

        }catch(\Exception $e){ // If its a fresh install, this handles shutdown of the app to PDOException
            dd($e);
        }
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
