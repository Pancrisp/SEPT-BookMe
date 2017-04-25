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

Route::get('/staffbooking', function () {
	$value = session('user');
	if (!isset($value))
		return view('login');
	else
		return view('newBooking');
});

Route::post('/register', 'RegistrationController@register');
Route::post('/dashboard', 'DashboardController@login');
Route::get('/dashboard', 'DashboardController@backToDashboard');

Route::get('/bookings/getByDate', 'SlotController@getSlotsByDate');
Route::get('/bookings/summary', 'BookingController@getBookingsByBusiness');

Route::get('/newstaff', 'EmployeeController@newStaff');
Route::post('/addstaff', 'EmployeeController@addStaff');
Route::get('/emp/availability/get', 'EmployeeController@getAvailability');

Route::get('/newroster', 'RosterController@newRoster');
Route::post('/addroster', 'RosterController@addRoster');
Route::get('/viewroster', 'RosterController@showRoster');
