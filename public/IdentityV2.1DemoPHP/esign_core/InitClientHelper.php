<?php

namespace tech\esign_core;
use tech\esign_constant\Config;
use tech\esign_utils\HttpHelper;

class InitClientHelper
{

    public static function doRegister(){
        $config = Config::$config;
        $data = [
            'projectConfig'=>[
                'projectId'=>$config['projectId'],
                'projectSecret'=>$config['projectSecret'],
                'itsmApiUrl'=>$config['openAPIUrl']
            ]
        ];
        $result = HttpHelper::doPost($config['warUrl'].Config::$urlMap['INIT_URL'],$data);
        return $result;
    }



}
