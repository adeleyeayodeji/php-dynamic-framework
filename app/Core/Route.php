<?php
//namespace
namespace App\Core;

use App\Core\Request;

class Route
{
    public static $uri;
    public static function route()
    {
        $route = [
            '/',
            '/blog/{id}/{title}',
            '/blog/{id}/{title}/{slug}',
            '/about-us'
        ];
        return $route;
    }
    //validate uri
    public static function validateURL($uri, $controlargs = [])
    {
        $route = self::route();
        self::$uri = $uri;
        //check if url has parameters and use as variables
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
            } else if (preg_grep($uri, $route)) {
                //do nothing
                echo 'do nothing';
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
            } else if (in_array(self::$uri, $route)) {
                //do nothing
            } else {
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

    /*
    * Check if route is valid
    * @return bool
    */
    public static function routeValid()
    {
        $session = Session::get('uri', []);
    }

    //404
    public static function notFound()
    {
        echo "404";
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
