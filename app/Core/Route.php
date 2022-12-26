<?php
//namespace
namespace App\Core;

use App\Core\Request;

class Route
{
    public static $routes = [];
    //validate uri
    public static function validateURL($uri, $controlargs)
    {
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
                //check if $controlargs is callable
                if (is_callable($controlargs)) {
                    //pass as variables
                    $matches = array_slice($matches, 1);
                    $matches = array_combine(array_keys($matches_data), $matches);
                    $controlargs(
                        new Request,
                        $matches
                    );
                } else {
                    //check if controller exists
                    $controller = "App\\Controller\\{$controlargs[0]}";
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
                }
            }
        } else {
            //check if url is equal to uri
            if (Request::uri() == $uri) {
                //check if $controlargs is callable
                if (is_callable($controlargs)) {
                    $controlargs(
                        new Request
                    );
                } else {
                    //check if controller exists
                    $controller = "App\\Controller\\{$controlargs[0]}";
                    if (!class_exists($controller)) {
                        //404
                        self::classNotFound($controlargs[0]);
                    }
                    $controller = new $controller;
                    $controller->{$controlargs[1]}(
                        new Request
                    );
                }
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
        self::routeHandler($uri, $controlargs, 'GET');
    }

    //post
    public static function post($uri, $controlargs = [])
    {
        self::routeHandler($uri, $controlargs, 'POST');
    }

    //put
    public static function put($uri, $controlargs = [])
    {
        self::routeHandler($uri, $controlargs, 'PUT');
    }

    //delete
    public static function delete($uri, $controlargs = [])
    {
        self::routeHandler($uri, $controlargs, 'DELETE');
    }

    //any
    public static function any($uri, $controlargs = [])
    {
        self::routeHandler($uri, $controlargs, 'ANY');
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

    //handler
    public static function routeHandler($uri, $controlargs = [], $method)
    {
        //convert uri to preg
        if (preg_match_all('/\{([a-zA-Z0-9]+)\}/', $uri, $matches)) {
            //convert to dynamic regex
            $uri2 = preg_replace('/\{([a-zA-Z0-9]+)\}/', '([a-zA-Z0-9]+)', $uri);
            //escape /
            $uri2 = str_replace('/', '\/', $uri2);
            //convert to dynamic regex
            $uri2 = "/^{$uri2}$/";
        } else {
            $uri2 = $uri;
            $matches = false;
        }
        //save routes
        self::$routes[] = [
            'uri' => $uri,
            'preg' => $uri2,
            'matches' => $matches,
            'controlargs' => $controlargs,
            'method' => $method
        ];
    }

    //run
    public static function run()
    {
        //check if routes is empty
        if (empty(self::$routes)) {
            self::notFound();
        }
        //current method
        $method = Request::method();
        //current uri
        $uri = Request::uri();
        //page not found
        $pageNotFound = [];
        //loop through routes
        foreach (self::$routes as $route) {
            //check if matches is not false
            if ($route['matches'] !== false) {
                //check if uri is equal to preg
                if (preg_match($route['preg'], $uri, $matches)) {
                    //check if method is equal to route method
                    if ($method == $route['method'] || $route['method'] == 'ANY') {
                        //validate url
                        self::validateURL($route['uri'], $route['controlargs']);
                    } else {
                        self::notFoundHeader();
                    }
                    //page found
                    $pageNotFound[] = false;
                } else {
                    $pageNotFound[] = true;
                    //continue
                    continue;
                }
            } else {
                //check if uri is equal to route uri
                if ($uri == $route['uri']) {
                    //check if method is equal to route method
                    if ($method == $route['method'] || $route['method'] == 'ANY') {
                        //validate url
                        self::validateURL($route['uri'], $route['controlargs']);
                    } else {
                        self::notFoundHeader();
                    }
                    //page found
                    $pageNotFound[] = false;
                } else {
                    $pageNotFound[] = true;
                    //continue
                    continue;
                }
            }
        }
        //check if page not found
        if (in_array(false, $pageNotFound)) {
            //page found
            return;
        } else {
            //page not found
            self::notFound();
        }
    }
}
