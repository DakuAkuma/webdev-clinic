<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

/* Навигация по сайту, основная навигация */
Route::get('/', function () {
    return view('main');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/users/sign_in', function () {
    return view('auth');
});

Route::get('/users/add', function () {
    return view('add');
});
