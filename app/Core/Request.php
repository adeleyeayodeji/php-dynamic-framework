<?php

namespace App\Core;

class Request
{
    public static function uri()
    {
        //check if ssl
        if (!self::ssl()) {
            $t = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
            //get base
            $b = self::base();
            //check if base is in uri
            if (strpos($t, $b) !== false) {
                //remove first part of uri
                $t = substr($t, strlen($b));
            }
            return $t ?: '/';
        } else {
            return trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        }
    }

    //check base path 
    public static function base()
    {
        return trim(dirname($_SERVER['SCRIPT_NAME']), '/');
    }

    //check ssl
    public static function ssl()
    {
        return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    }

    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function isPost()
    {
        return self::method() === 'POST';
    }

    public static function isGet()
    {
        return self::method() === 'GET';
    }

    public static function isPut()
    {
        return self::method() === 'PUT';
    }

    public static function isDelete()
    {
        return self::method() === 'DELETE';
    }

    public static function isPatch()
    {
        return self::method() === 'PATCH';
    }

    public static function isHead()
    {
        return self::method() === 'HEAD';
    }

    public static function isOptions()
    {
        return self::method() === 'OPTIONS';
    }

    public static function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    public static function isSecure()
    {
        return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    }

    public static function isCli()
    {
        return php_sapi_name() === 'cli';
    }

    public static function isJson()
    {
        return isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'application/json';
    }

    public static function isXml()
    {
        return isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'application/xml';
    }

    public static function isHtml()
    {
        return isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'text/html';
    }

    public static function isForm()
    {
        return isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'application/x-www-form-urlencoded';
    }

    //params
    public static function get($key = null, $default = null)
    {
        if (is_null($key)) {
            return $_GET;
        }

        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }

    public static function post($key = null, $default = null)
    {
        if (is_null($key)) {
            return $_POST;
        }

        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }

    //any
    public static function any($key = null, $default = null)
    {
        if (is_null($key)) {
            return array_merge($_GET, $_POST);
        }

        return isset($_GET[$key]) ? $_GET[$key] : (isset($_POST[$key]) ? $_POST[$key] : $default);
    }

    // all
    public static function all($key = null, $default = null)
    {
        if (is_null($key)) {
            return array_merge($_GET, $_POST);
        }

        return isset($_GET[$key]) ? $_GET[$key] : (isset($_POST[$key]) ? $_POST[$key] : $default);
    }
}
