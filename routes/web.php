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
Route::get('/', 'DefaultRoutesController@main');

Route::get('/about', 'DefaultRoutesController@about');

Route::get('/users/sign_in', 'DefaultRoutesController@sign_in');

Route::get('/users/sign_up', 'DefaultRoutesController@sign_up');

/* User profile */
Route::get('/users/profile', 'DefaultRoutesController@profile');

/* Auth route */
Route::post('/users/sign_in/validate', 'LoginController@authenticate');

/* Logout route */
Route::get('/logout', 'LoginController@logout');

/* CRUD controller */

/* Creating by admin route */
Route::post('/create', 'CRUDController@create');

/* Patient create route */
Route::post('/users/sign_up/validate', 'PatientController@sign_up_validate');

/* Read route */
Route::get('/{userRole}/{id}/view', 'CRUDController@read');

/* Update routes */
Route::post('/{userRole}/{id}/update', 'CRUDController@update');

/* Delete routes */
Route::get('/{id}/delete', 'CRUDController@delete');

/* Get page to add visit */
Route::get('/visits/add', 'PatientController@visits');