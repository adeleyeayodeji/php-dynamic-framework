<?php

use App\Core\Route;

//home
// Route::get('/', [HomeController::class, 'index']);
// Route::get('/blog/{id}/{title}', [HomeController::class, 'blog']);
Route::get('/blog/{id}/{title}/{slug}', [HomeController::class, 'blog']);
//about us
// Route::get('/about-us', [HomeController::class, 'about']);
