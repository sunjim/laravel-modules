<?php

namespace Houdunwang\Module;

use Illuminate\Support\ServiceProvider;
use Houdunwang\Module\Commands\PermissionCreateCommand;

class LaravelServiceProvider extends ServiceProvider
{

    public function boot()
    {
        if($this->app->runningInConsole()){
            $this->commands([PermissionCreateCommand::class]);
        }
        //配置文件
        $this->publishes(
            [
                __DIR__.'/zx_module.php' => config_path('zx_module.php'),
            ]
        );
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'HDModule',
            function() {
                return new Provider();
            }
        );
    }
}
