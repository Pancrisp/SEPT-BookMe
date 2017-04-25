<?php

/*
|--------------------------------------------------------------------------
| homepage and registration related routes
|--------------------------------------------------------------------------
 */
Route::get('/', function(){ return view('login'); });
Route::get('/signup', function(){ return view('signup'); });
// Form submission
Route::post('/register', 'RegistrationController@register');

/*
|--------------------------------------------------------------------------
| dashboard related routes
|--------------------------------------------------------------------------
 */
Route::post('/dashboard', 'DashboardController@login');
Route::get('/dashboard', 'DashboardController@backToDashboard');

/*
|--------------------------------------------------------------------------
| booking related routes
|--------------------------------------------------------------------------
 */
Route::get('/booking/summary', 'BookingController@showBookingSummary');
Route::get('/booking/owner', function(){ return view('newBooking');});
// AJAX
Route::get('/booking/get/byDate', 'SlotController@getSlotsByDate');

/*
|--------------------------------------------------------------------------
| staff related routes
|--------------------------------------------------------------------------
 */
Route::get('/staff/add', 'EmployeeController@addStaffForm');
Route::get('staff/summary', 'EmployeeController@viewStaffSummary');
Route::get('staff/update', 'EmployeeController@showStaffUpdateForm');
// Form submission
Route::post('/staff/add/submit', 'EmployeeController@addStaff');
Route::post('staff/update/submit', 'EmployeeController@updateStaffAvailableDays');
// AJAX
Route::get('/staff/availability/get', 'EmployeeController@getAvailability');

/*
|--------------------------------------------------------------------------
| roster related routes
|--------------------------------------------------------------------------
 */
Route::get('/roster/add', 'RosterController@addRosterForm');
Route::get('/roster/summary', 'RosterController@showRoster');
// Form submission
Route::post('/roster/add/submit', 'RosterController@addRoster');
