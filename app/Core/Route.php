<?php
//namespace
namespace App\Core;

use App\Core\Request;

class Route
{
    //validate uri
    public static function validateURL($uri, $controlargs = [])
    {
        //check if url has parameters and use as variables
        self::saveSession($uri);
        if (preg_match_all('/\{([a-zA-Z0-9]+)\}/', $uri, $matches)) {
            //convert to dynamic regex
            $uri = preg_replace('/\{([a-zA-Z0-9]+)\}/', '([a-zA-Z0-9]+)', $uri);
            //escape /
            $uri = str_replace('/', '\/', $uri);
            //convert to dynamic regex
            $uri = "/^{$uri}$/";
            //pass as variables
            $matches_data = [];
            foreach ($matches[1] as $match) {
                $matches_data[$match] = null;
            }
            //check if url is equal to uri
            if (preg_match($uri, Request::uri(), $matches)) {
                $controller = "App\\Controller\\{$controlargs[0]}";
                //check if controller exists
                if (!class_exists($controller)) {
                    //404
                    self::classNotFound($controlargs[0]);
                }
                $controller = new $controller;
                //pass as variables
                $matches = array_slice($matches, 1);
                $matches = array_combine(array_keys($matches_data), $matches);
                $controller->{$controlargs[1]}(
                    new Request,
                    $matches
                );
            } else {
                self::notFound();
            }
        } else {
            //check if url is equal to uri
            if (Request::uri() == $uri) {
                $controller = "App\\Controller\\{$controlargs[0]}";
                //check if controller exists
                if (!class_exists($controller)) {
                    //404
                    self::classNotFound($controlargs[0]);
                }
                $controller = new $controller;
                $controller->{$controlargs[1]}(
                    new Request
                );
            } else {
                //404
                self::notFound();
            }
        }
    }

    //save list session
    public static function saveSession($uri)
    {
        //store uri in session
        $uriSession = Session::get('uri', []);
        $uriSession[] = $uri;
        //array unique
        $uriSession = array_unique($uriSession);
        //store in session
        Session::set('uri', $uriSession);
    }

    public static function get($uri, $controlargs = [])
    {
        if (Request::isGet()) {
            self::validateURL($uri, $controlargs);
        } else {
            self::notFoundHeader();
        }
    }

    //post
    public static function post($uri, $controlargs = [])
    {
        if (Request::isPost()) {
            self::validateURL($uri, $controlargs);
        } else {
            self::notFoundHeader();
        }
    }

    //put
    public static function put($uri, $controlargs = [])
    {
        if (Request::isPut()) {
            self::validateURL($uri, $controlargs);
        } else {
            self::notFoundHeader();
        }
    }

    //delete
    public static function delete($uri, $controlargs = [])
    {
        if (Request::isDelete()) {
            self::validateURL($uri, $controlargs);
        } else {
            self::notFoundHeader();
        }
    }

    //any
    public static function any($uri, $controlargs = [])
    {
        self::validateURL($uri, $controlargs);
    }

    //404
    public static function notFound()
    {
        if (!Session::routeValid()) {
            echo "404 <br>";
            // exit;
        }
    }

    //404 header
    public static function notFoundHeader()
    {
        echo "No route found for this request method";
        exit;
    }

    //class not found
    public static function classNotFound($class)
    {
        echo "'$class' Class not found";
        exit;
    }
}
