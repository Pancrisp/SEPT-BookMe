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

Route::get('/newstaff', function () {
    return view('newstaff');
});

Route::post('/register', 'RegistrationController@register');
Route::post('/dashboard', 'AuthenticationController@login');

Route::get('/bookings/getByDate', 'BookingController@getBookingsByDate');
Route::get('/bookings/summary/{id}', 'BookingController@getBookingsByBusiness');
