<?php

/*
|--------------------------------------------------------------------------
| dashboard/homepage related routes
|--------------------------------------------------------------------------
 */
Route::post('/', 'DashboardController@loadDashboard');
Route::get('/', 'DashboardController@backToDashboard');

/*
|--------------------------------------------------------------------------
| authentication and registration related routes
|--------------------------------------------------------------------------
 */
Route::get('/login', function(){ return view('login'); });
Route::get('/logout', 'AuthenticationController@logout');
Route::get('/signup', function(){ return view('signup'); });
// Form submission
Route::post('/register', 'RegistrationController@register');

/*
|--------------------------------------------------------------------------
| booking related routes
|--------------------------------------------------------------------------
 */
Route::get('/booking/summary', 'BookingController@showBookingSummary');
Route::get('/booking/owner', function(){ return view('businessBooking');});
// Form submission
Route::post('/book', 'BookingController@addBookingForm');
Route::post('/booking', 'BookingController@addBooking');
// AJAX
Route::get('/booking/get/byDate', 'SlotController@getSlotsByDate');

/*
|--------------------------------------------------------------------------
| staff related routes
|--------------------------------------------------------------------------
 */
Route::get('/staff/add', 'EmployeeController@addStaffForm');
Route::get('/staff/summary', 'EmployeeController@viewStaffSummary');
Route::get('/staff/update', 'EmployeeController@showStaffUpdateForm');
// Form submission
Route::post('/staff/add/submit', 'EmployeeController@addStaff');
Route::post('/staff/update/submit', 'EmployeeController@updateStaffAvailableDays');
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
// AJAX
Route::get('/roster/staff/get/byActivity', 'RosterController@getStaffByActivity');