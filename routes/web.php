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

/* User profile */
Route::get('/profile', 'DefaultRoutesController@profile');

/* CRUD controller */

/* Creating by admin route */
Route::post('/create', 'CRUDController@create');

/* Read routes */
Route::get('/records/view', 'CRUDController@read');

/* Update route */
Route::post('/profile/update', 'CRUDController@update');

/* Delete route */
Route::get('/profile/delete', 'CRUDController@delete');

/* Visits block */
/* Get page to add visit */
Route::get('/visits', 'VisitsController@getVisits');
/* POST for validation */
Route::post('/visits/add/{medic_id}', 'VisitsController@newVisit');
/* Update visit */
Route::post('/visits/{visit_id}/update', 'VisitsController@visitUpdate');
/* Delete visit */
Route::get('/visits/{visit_id}/delete', 'VisitsController@visitDelete');

/* Records block */
/* Get page to add record */
Route::get('/records', 'RecordsController@getRecords');
/* POST for validation */
Route::post('/records/{patient_id}/add', 'RecordsController@addRecord');

/* Admin block */
Route::get('/admin', 'AdminController@index');
Route::post('/admin/create', 'CRUDController@create');
Route::get('/admin/update', 'AdminController@updatePage');
Route::post('/admin/{user_id}/update', 'AdminController@updateUser');
Route::post('/patient/{patient_id}/update', 'AdminController@updatePatient');
Route::post('/medic/{medic_id}/update', 'AdminController@updateDoctor');
Route::post('/employee/{employee_id}/update', 'AdminController@updateEmployee');
Route::get('/admin/{user_id}/delete', 'AdminController@deleteUser');
Route::get('/patient/{patient_id}/delete', 'AdminController@deletePatient');
Route::get('/medic/{medic_id}/delete', 'AdminController@deleteDoctor');
Route::get('/employee/{employee_id}/delete', 'AdminController@deleteEmployee');


/* Auth routes */
Auth::routes();

Route::get('/home', function (){
    return redirect('/profile');
});

/* Logout route */
Route::get('/logout', function () {
    session()->flush();
    return redirect('/');
});
