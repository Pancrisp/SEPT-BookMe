<?php

namespace App\Http\Controllers;


use App\Activity;
use App\Booking;
use App\Business;
use App\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class BookingController
{
    /**
     * show booking summary for certain business
     * only accessible when logged in
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showBookingSummary()
    {
        // redirect to login page if not authenticated, or incorrect user type
        if ( ! Auth::check() || Auth::user()['user_type'] != 'business')
            return Redirect::to('/login');

        // get auth and business ID
        $auth = Auth::user();
        $businessID = $auth['foreign_id'];

        // get today's date using Carbon
        $today = Carbon::now()->toDateString();

        // get all bookings with customer details for this business from today onwards
        $allBookings = Booking::join('customers', 'bookings.customer_id', 'customers.customer_id')
            ->where('bookings.business_id', $businessID)
            ->where('bookings.date', '>=', $today)
            ->orderBy('bookings.date')
            ->orderBy('bookings.start_time')
            ->get();

        // get new bookings with customer details for this business made today
        $newBookings = Booking::join('customers', 'bookings.customer_id', 'customers.customer_id')
            ->where('bookings.business_id', $businessID)
            ->whereDate('bookings.created_at', $today)
            ->orderBy('bookings.date')
            ->orderBy('bookings.start_time')
            ->get();

        // return the view with data required
        return view('bookingSummary', compact('allBookings', 'newBookings'));
    }

    /**
     * This is called when submitting view available booking form
     * validates data
     * only accessible by authenticated user
     *
     * if validation fails, redirect back with input and error messages
     * if validation passes, return the booking form view
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addBookingForm(Request $request)
    {
        // redirect to login page if not authenticated
        if ( ! Auth::check())
            return Redirect::to('/login');

        // this validates the data
        $validator = $this->availabilityValidator($request->all());

        // when validation fails, redirect back with input and error messages
        if($validator->fails()) {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator);
        }

        // get attributes from request
        $businessID = $request['business'];
        $date = $request['date'];

        // get employees working on that day for this business
        $employees = Employee::join('rosters', 'rosters.employee_id', 'employees.employee_id')
            ->where('employees.business_id', $businessID)
            ->where('rosters.date', $date)
            ->get();

        // get activities of this business
        $activities
            = Activity::where('business_id', $businessID)
            ->get();

        // get current bookings of this business on that date, group by activities
        $bookings = [];
        foreach($activities as $activity)
        {
            $bookings[$activity['activity_id']]
                = Booking::join('employees', 'bookings.employee_id', 'employees.employee_id')
                ->where('bookings.business_id', $businessID)
                ->where('bookings.activity', $activity['activity_name'])
                ->where('bookings.date', $date)
                ->orderBy('bookings.employee_id')
                ->get();
        }

        // get the business
        $business = Business::find($businessID);

        return view('newBooking', compact('request', 'employees', 'activities', 'bookings', 'business'));
    }

    /**
     * @param Request $request
     */
    public function addBooking(Request $request)
    {

    }

    /**
     * validate incoming data for viewing available bookings
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function availabilityValidator(array $data)
    {
        return Validator::make($data, [
            'customer'  => 'required',
            'business'  => 'required',
            'date'      => 'required|date|after:today'
        ]);
    }
}
