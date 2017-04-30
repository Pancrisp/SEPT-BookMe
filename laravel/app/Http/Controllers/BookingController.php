<?php

namespace App\Http\Controllers;


use App\Activity;
use App\Booking;
use App\Business;
use App\Customer;
use App\Employee;
use App\Roster;
use App\Slot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            ->join('employees', 'employees.employee_id', 'bookings.employee_id')
            ->join('activities', 'employees.activity_id', 'activities.activity_id')
            ->where('bookings.business_id', $businessID)
            ->where('bookings.date', '>=', $today)
            ->orderBy('bookings.date')
            ->orderBy('bookings.start_time')
            ->get();

        // get new bookings with customer details for this business made today
        $newBookings = Booking::join('customers', 'bookings.customer_id', 'customers.customer_id')
            ->join('employees', 'employees.employee_id', 'bookings.employee_id')
            ->join('activities', 'employees.activity_id', 'activities.activity_id')
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
     * only accessible authenticated user
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
        if ( ! Auth::check() )
            return Redirect::to('/login');

        // get auth and details
        $auth = Auth::user();
        $type = $auth['user_type'];

        // this validates the data according to type
        if($type == 'customer')
            $validator = $this->customerBookingValidator($request->all());
        else
            $validator = $this->businessBookingValidator($request->all());

        // when validation fails, redirect back with input and error messages
        if($validator->fails()) {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator);
        }

        // get customer when submitting by business owner
        if($type == 'business')
        {
            // get customer from DB
            $customer
                = Customer::where('username', $request['username'])
                ->first();

            // save customer_id into request
            $request['customer'] = $customer->customer_id;
        }

        // load the form when validation passed
        return $this->loadBookingForm($request);
    }

    /**
     * called when submitting add booking form
     * validate incoming request
     *
     * when validation fails, redirect back to the form by returning the view
     * when validation passes, save to DB and return a confirmation page
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addBooking(Request $request)
    {
        $validator = $this->creatingBookingValidator($request->all());

        // when validation fails, load booking form with error
        if($validator->fails() || !$this->canBeBooked($request->all())) {
            $error = 'Slot selected is not valid';
            return $this->loadBookingForm($request, $error);
        }

        // save the booking to DB when validation passes
        $booking = $this->saveBooking($request->all());

        // when booking is successfully saved, return confirmation page
        if($booking)
            return $this->loadConfirmationPage($booking);
    }

    /**
     * display bookings based on date and employee
     * only accessible by business owner
     *
     * date is passed in the request, if not by default tomorrow
     * employees should be those rostered on the date
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAvailability(Request $request)
    {
        // redirect to login page if not authenticated, or incorrect user type
        if ( ! Auth::check() || Auth::user()['user_type'] != 'business')
            return Redirect::to('/login');

        // get auth and business ID
        $auth = Auth::user();
        $businessID = $auth['foreign_id'];

        // get start and end date of next 7 days
        $tomorrow = Carbon::now()->addDay();
        $aWeekLater = Carbon::now()->addWeek();

        // get business
        $business = Business::find($businessID);

        // get dates of next 7 days from roster table of this business
        $dates = Roster::select('rosters.date')
            ->join('employees', 'employees.employee_id', 'rosters.employee_id')
            ->where('employees.business_id', $businessID)
            ->whereBetween('rosters.date', array($tomorrow->toDateString(), $aWeekLater->toDateString()))
            ->orderBy('rosters.date', 'asc')
            ->groupBy('rosters.date')
            ->get();

        // get date selected from request, if not set, by default tomorrow
        $dateSelected = isset($request['date'])? $request['date']: $tomorrow->toDateString();

        // get employees rostered on the date selected
        $employees = Employee::join('rosters', 'rosters.employee_id', 'employees.employee_id')
            ->join('activities', 'activities.activity_id', 'employees.activity_id')
            ->where('employees.business_id', $businessID)
            ->where('rosters.date', $dateSelected)
            ->get();

        // loop through each employee to get bookings
        $bookings = [];
        foreach ($employees as $employee)
        {
            $bookings[$employee['employee_id']]
                = Booking::join('employees', 'employees.employee_id', 'bookings.employee_id')
                ->join('activities', 'activities.activity_id', 'employees.activity_id')
                ->join('customers', 'customers.customer_id', 'bookings.customer_id')
                ->where('bookings.employee_id', $employee['employee_id'])
                ->where('bookings.date', $dateSelected)
                ->orderBy('bookings.start_time', 'asc')
                ->get();
        }

        return view('availability', compact('bookings', 'employees', 'dates', 'business', 'dateSelected'));
    }

    /**
     * load booking form using request passed in
     * can be accessed by authenticated user when looking for bookings (post)
     * also accessed by showing error after posting add booking form (get)
     *
     * @param $request
     * @param string $error
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @internal param $type
     */
    private function loadBookingForm($request, $error="")
    {
        // get attributes from request
        $businessID = $request['business'];
        $date = $request['date'];

        // get employees working on that day for this business
        $employees = Employee::select('employees.employee_id', 'employees.employee_name')
            ->join('rosters', 'rosters.employee_id', 'employees.employee_id')
            ->where('employees.business_id', $businessID)
            ->where('rosters.date', $date)
            ->groupBy('employees.employee_id', 'employees.employee_name')
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
                ->where('employees.activity_id', $activity['activity_id'])
                ->where('bookings.date', $date)
                ->orderBy('bookings.employee_id')
                ->get();
        }

        // get the business
        $business = Business::find($businessID);

        return view('newBooking', compact('request', 'employees', 'activities', 'bookings', 'business', 'error'));
    }

    /**
     * load a confirmation page after booking created successfully
     * display: date, time, activity, staff
     *
     * @param $bookingSaved
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function loadConfirmationPage($bookingSaved)
    {
        // retrieve booking with more details by joining tables
        $booking = Booking::join('employees', 'employees.employee_id', 'bookings.employee_id')
            ->join('activities', 'activities.activity_id', 'employees.activity_id')
            ->find($bookingSaved->booking_id);

        // retrieve business from DB
        $business = Business::find($bookingSaved->business_id);

        return view('confirmation', compact('business', 'booking'));
    }

    /**
     * validate incoming data for viewing available bookings by customer
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function customerBookingValidator(array $data)
    {
        return Validator::make($data, [
            'customer'  => 'required',
            'business'  => 'required',
            'date'      => 'required|date|after:today'
        ]);
    }

    /**
     * validate incoming data for viewing available bookings by business owner
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function businessBookingValidator(array $data)
    {
        return Validator::make($data, [
            'username'  => 'required|exists:customers',
            'business'  => 'required',
            'date'      => 'required|date|after:today'
        ]);
    }

    /**
     * validate incoming data for creating a new booking
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function creatingBookingValidator(array $data)
    {
        $validator = Validator::make($data, [
            'customer'  => 'required',
            'business'  => 'required',
            'date'      => 'required|date|after:today',
            'service'   => 'required',
            'employee'  => 'required',
            'time'      => 'required'
        ]);

        return $validator;
    }

    /**
     * validate if slot already booked when creating new booking
     *
     * @param array $data
     * @return bool
     */
    private function canBeBooked(array $data)
    {
        // get slot of same date, time, and employee, if exists
        $slot = Slot::join('bookings', 'bookings.booking_id', 'slots.booking_id')
            ->where('bookings.date', $data['date'])
            ->where('bookings.employee_id', $data['employee'])
            ->where('slots.slot_time', $data['time'].':00')
            ->get();

        // return false if such slot already booked
        if(count($slot))
            return false;
        else
            return true;
    }

    /**
     * called when adding booking is validated
     * save booking to DB
     *
     * @param array $data
     * @return Booking
     */
    private function saveBooking(array $data)
    {
        // create booking and save to DB
        $booking =  Booking::create([
            'date' => $data['date'],
            'start_time' => $data['time'].':00',
            'customer_id' => $data['customer'],
            'business_id' => $data['business'],
            'employee_id' => $data['employee']
        ]);

        $this->saveSlots($data, $booking);

        return $booking;
    }

    /**
     * save slots to DB according to booking created
     *
     * @param array $data
     * @param $booking
     */
    private function saveSlots(array $data, $booking)
    {
        // retrieving data
        $time = $data['time'].':00';
        $businessID = $data['business'];
        $employeeID = $data['employee'];

        // retrieve activity from DB and get num_of_slots
        $activity = Activity::join('employees', 'activities.activity_id', 'employees.activity_id')
            ->where('employees.employee_id', $employeeID)
            ->first();
        $numOfSlots = $activity->num_of_slots;

        // retrieve business
        $business = Business::find($businessID);

        // set start time
        $startTime = new Carbon($time);

        // save each slot to DB
        for($k=0; $k<$numOfSlots; $k++)
        {
            // create the slot
            Slot::create([
                'slot_time' => $startTime->toTimeString(),
                'booking_id' => $booking->booking_id
            ]);

            // increment time period according to config from DB
            $startTime->addMinute($business->slot_period);
        }
    }
}
