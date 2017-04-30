<?php

/*
|--------------------------------------------------------------------------
| dashboard/homepage related routes
|--------------------------------------------------------------------------
 */
Route::get('/', 'DashboardController@loadDashboard');

/*
|--------------------------------------------------------------------------
| authentication and registration related routes
|--------------------------------------------------------------------------
 */
Route::get('/login', function(){ return view('login'); });
Route::get('/register', function(){ return view('signup'); });
Route::get('/logout', 'AuthenticationController@logout');
// Form submission
Route::post('/login/submit', 'AuthenticationController@login');
Route::post('/register/submit', 'RegistrationController@register');

/*
|--------------------------------------------------------------------------
| booking related routes
|--------------------------------------------------------------------------
 */
Route::get('/booking/summary', 'BookingController@showBookingSummary');
Route::get('/booking/owner', function(){ return view('businessBooking');});
Route::get('/booking/availability', 'BookingController@showAvailability');
// Form submission
Route::post('/book', 'BookingController@addBookingForm');
Route::post('/booking', 'BookingController@addBooking');

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
Route::get('/staff/get', 'EmployeeController@getEmployeeDetails');

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


Route::get('hi', function(\Illuminate\Http\Request $request){
    $number = isset($request['hi'])? $request['hi'] : 0;

    dd($number);
});