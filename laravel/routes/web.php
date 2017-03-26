<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login');
});

Route::get('/signup', function () {
    return view('signup');
});

Route::get('/customerDashboard', function () {
    return view('customerDashboard');
});

Route::get('/businessOwnerDashboard', function () {
    return view('businessOwnerDashboard');
});

Route::get('/newstaff', function () {
    return view('newstaff');
});

Route::post('/register', 'RegistrationController@register');
Route::post('/authenticate', 'AuthenticationController@login');
