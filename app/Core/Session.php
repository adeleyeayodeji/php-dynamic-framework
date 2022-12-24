<?php

namespace App\Core;

/**
 * Session
 * @package App\Core
 */
class Session
{
    /**
     * Session constructor.
     */
    public function __construct()
    {
        if (version_compare(phpversion(), '5.4.0', '<')) {
            if (session_id() == '') {
                session_start();
            }
        } else {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
        }
    }

    /**
     * Set session
     * @param $key
     * @param $val
     */
    public static function set($key, $val)
    {
        return $_SESSION[$key] = $val;
    }

    /**
     * Get session
     * @param $key
     * @return mixed
     */
    public static function get($key, $default = false)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        } else {
            return false || $default;
        }
    }

    /**
     * Destroy session
     * @return bool
     */
    public static function destroy()
    {
        session_start();
        session_destroy();
        return true;
    }

    /**
     * Remove session
     * @param $key
     */
    public static function remove($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Check session
     * @param $key
     * @return bool
     */
    public static function check($key)
    {
        if (isset($_SESSION[$key])) {
            return true;
        } else {
            return false;
        }
    }

    //routeValid
    public static function routeValid()
    {
        $session = Session::get('uri', []);
        //if Request::uri() in array of session

    }
}
