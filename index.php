<?php

//root path
define('BP', __DIR__ . '/');

//enabling displaying php errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

//path were included classes would be find
$includePaths = implode(PATH_SEPARATOR, [
    BP . 'App/Model',
    BP . 'App/Controller'
]);

set_include_path($includePaths);

//register autoloader, to auto include classes when needed
spl_autoload_register(function($class)
{

    $classPath = strtr($class, '\\', DIRECTORY_SEPARATOR) . '.php';

    if(file_exists(BP . 'App/Model/' . $classPath) || file_exists(BP . 'App/Controller/' . $classPath)) {
        return include $classPath;
    }

});

App::start();