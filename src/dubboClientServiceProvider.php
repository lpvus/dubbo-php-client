<?php
/**
 * Created by PhpStorm.
 * User: yolo
 * Date: 2017/7/26
 * Time: 下午4:34
 */

namespace dubbo;

use Illuminate\Support\ServiceProvider;

class dubboClientServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/config/dpc.php', 'dpc');
    }

    public function register()
    {
        $this->app->singleton('dubbo.client', function($app) {
            $options = $app['config']->get('dpc.default');
            return new dubboClient($options);
        });
    }
}