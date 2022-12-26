<?php

namespace App\Controller;

use App\Core\Request;

class HomeController
{
    /**
     * @param Request $request
     */
    public function about(Request $request)
    {
        echo 'about us';
    }

    //index
    public function index(Request $request)
    {
        echo 'home';
    }

    //blog
    public function blog(Request $request, $args)
    {
        echo '<pre>';
        var_dump($args);
        echo '</pre>';
    }

    //contact
    public function contact(Request $request)
    {
        echo '<h1>How are you?</h1>';
    }
}
