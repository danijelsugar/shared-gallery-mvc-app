<?php

//root path
define('BP', dirname(dirname(__FILE__)) . '/' );

//enabling displaying php errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

//path were included classes would be find
$includePaths = implode(PATH_SEPARATOR, [
    BP . 'App/Model',
    BP . 'App/Model/Entity',
    BP . 'App/Controller'
]);

set_include_path($includePaths);

//register autoloader, to auto include classes when needed
spl_autoload_register(function($class)
{

    $classPath = strtr($class, '\\', DIRECTORY_SEPARATOR) . '.php';

    if($file = stream_resolve_include_path($classPath)) {
        include $file;
        return true;
    }
    return false;

});

App::start();