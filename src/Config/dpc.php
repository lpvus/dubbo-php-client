<?php
/**
 * Created by PhpStorm.
 * User: yolo
 * Date: 2017/7/26
 * Time: 下午4:29
 */
return [
    'default'=>[
        "registry_address" => env('DUBBO_REG_ADDR','127.0.0.1:2181'),
        'version' => env('DUBBO_VER','1.0'),
        'group' =>env('DUBBO_GROUP',null),
        'protocol' =>env('DUBBO_PROTOCOL','hessian')
    ]
];