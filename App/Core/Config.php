<?php

namespace Gallery\Core;

class Config 
{
    public static function get(string $key)
    {
        $config = require BP . 'App/config.php';
        return $config[$key];
    }
}