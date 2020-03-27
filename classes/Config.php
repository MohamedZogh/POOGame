<?php


class Config
{
    private $settings = [];
    private static $instance;

    public function __construct()
    {
        $this->settings = require './config/config.php';
    }

    public function getInstance(){
        if(is_null(self::$instance)){
            self::$instance = new Config();
        }
        return self::$instance;
    }

    public function get($key){
        if (!isset($this->settings[$key])){
            return null;
        }
        return $this->settings[$key];
    }
}