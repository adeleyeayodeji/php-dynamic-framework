<?php

use App\Core\Request;
use App\Core\Route;

$route = new Route;
//home
$route::get('/', [HomeController::class, 'index']);
$route::get('/blog/{id}/{title}', [HomeController::class, 'blog']);
$route::get('/blog/{id}/{title}/{slug}', [HomeController::class, 'blog']);
//about us
$route::get('/about-us', [HomeController::class, 'about']);
//contact us
$route::get('/contact-us', [HomeController::class, 'contact']);

//run
$route::run();
