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

Route::get('/booking/get/byDate', 'SlotController@getSlotsByDate');
Route::get('/booking/summary', 'BookingController@getBookingsByBusiness');

Route::get('/staff/add', 'EmployeeController@addStaffForm');
Route::post('/staff/add/submit', 'EmployeeController@addStaff');
Route::get('/staff/availability/get', 'EmployeeController@getAvailability');
Route::get('staff/summary', 'EmployeeController@viewStaffSummary');
Route::get('staff/update', 'EmployeeController@showStaffUpdateForm');
Route::post('staff/update/submit', 'EmployeeController@updateStaffAvailableDays');

Route::get('/roster/add', 'RosterController@addRosterForm');
Route::post('/roster/add/submit', 'RosterController@addRoster');
Route::get('/roster/summary', 'RosterController@showRoster');
