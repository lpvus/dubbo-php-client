<?php
/**
 * Created by PhpStorm.
 * User: yolo
 * Date: 2017/7/26
 * Time: 下午4:34
 */

namespace Dubbo;

use Illuminate\Support\ServiceProvider;

class DubboClientServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/dpc.php', 'dpc');
    }

    public function register()
    {
        $this->app->singleton('dubbo.client', function($app) {
            $options = $app['config']->get('dpc.default');
            return new DubboClient($options);
        });
    }
}