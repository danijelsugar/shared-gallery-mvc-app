<?php

class Request
{

    /**
     * @return string
     */
    public static function pathInfo()
    {
        if (isset($_SERVER['QUERY_STRING'])) {
            return $_SERVER['QUERY_STRING'];
        } elseif (isset($_SERVER['REDIRECT_PATH_INFO'])) {
            return ($_SERVER['REDIRECT_PATH_INFO']);
        } else {
            return '';
        }
    }

}