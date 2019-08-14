<?php

final class App 
{
    /**
     * Resolves controller/action
     */
    public static function start()
    {
        $pathInfo = Request::pathInfo();
        $pathInfo = trim($pathInfo, '/');
        $pathParts = explode('/', $pathInfo);
        
        // resolves controller
        if (!isset($pathParts[0]) || empty($pathParts[0])) {
            $controller = 'Home';
        } else {
            $controller = ucfirst(strtolower($pathParts[0]));
        }

        $controller .= 'Controller';

        //resolves action
        if (!isset($pathParts[1]) || empty($pathParts[1])) {
            $action = 'index';
        } else {
            $action = strtolower($pathParts[1]);
        }

        //resolves param
        if (!isset($pathParts[2]) || emty($pathParts)) {
            $param = 0;
        } else {
            $param = $pathParts[2];
        }

        if (class_exists($controller) && method_exists($controller, $action)) {
            $controllerInstance = new $controller();
            if ($param !== 0) {
                $controllerInstance->$action($param);
            } else {
                $controllerInstance->$action();
            }
        } else {
            //header("HTTP/1.0 404 Not Found");
        }


    }

    public static function config($key)
    {
        $config = require BP . 'app/config.php';
        return $config['key'];
    }
}