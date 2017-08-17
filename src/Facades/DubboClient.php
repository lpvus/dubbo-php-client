<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2017/7/26
 * Time: 下午4:43
 */

namespace Dubbo\Facades;

use Illuminate\Support\Facades\Facade;

class DubboClient extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'dubbo.client';
    }

}