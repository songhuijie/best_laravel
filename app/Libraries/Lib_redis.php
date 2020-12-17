<?php

/**
 * Created by PhpStorm.
 * User: songhuijie
 * Date: 2020-08-24
 * Time: 09:09
 */

namespace App\Libraries;

class Lib_redis
{

    const DETECTS_CLASSES_ALL = ':detects:classes:info:'; //检测分类all
    const DETECTS_CLASSES_NAME_ALL = ':detects:classes:name:info:'; //检测分类all


    public static function SplicingKey($key)
    {
        return config('app.redis_key_prefix') . $key;
    }


}
