<?php

namespace tech\esign_core;


use tech\esign_constant\Config;

class BaseHelper
{
    protected $openApiUrl;
    protected $urlMap;
    protected $config;
    public function __construct()
    {
        $this->config = Config::$config;
        $this->openApiUrl = Config::$config['openApiUrl'];
        $this->urlMap = Config::$urlMap;


    }

}
