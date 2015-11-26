<?php 
namespace rcbytes\Fetch;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class FetchServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {

    }


    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        
        $this->registerFetch();
        
    }
    private function registerFetch()
    {
        $this->app->bind('Fetch',function($app){
            return new Fetch($app);
        });
    }
}