<?php

namespace App\Http\Controllers;


use App\Activity;
use App\Booking;
use App\Business;
use App\Customer;
use App\Employee;
use App\Slot;
use Carbon\Carbon;
use DateTimeZone;
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
     * @param $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showBookingSummary($type)
    {
        // redirect to login page if not authenticated
        if ( ! Auth::check() )
            return Redirect::to('/login');

        // get auth and business ID
        $auth = Auth::user();
        $userID = $auth['foreign_id'];
        $userType = $auth['user_type'];

        // get summary types and save type selected
        $types = $this->getSummaryType($userType);
        $typeSelected = $type;

        // get today's date using Carbon
        $today = Carbon::now(new DateTimeZone('Australia/Melbourne'))->toDateString();

        // custom attribute needed for different user type
        if($userType == 'business')
            $customAttr = 'customers.*';
        else
            $customAttr = 'businesses.*';

        // join tables needed, only related to this user
        $bookings = Booking::join('customers', 'bookings.customer_id', 'customers.customer_id')
            ->join('employees', 'employees.employee_id', 'bookings.employee_id')
            ->join('activities', 'employees.activity_id', 'activities.activity_id')
            ->join('businesses', 'businesses.business_id', 'bookings.business_id')
            ->where('bookings.'.$userType.'_id', $userID)
            ->select('bookings.*', $customAttr, 'activities.activity_name', 'employees.employee_name')
            ->orderBy('bookings.date')
            ->orderBy('bookings.start_time');

        // get bookings according to type passed in the url
        if($type == 'recent')
            $bookings = $bookings
                ->whereDate('bookings.created_at', $today)
                ->get();
        elseif($type == 'today')
            $bookings = $bookings
                ->where('bookings.date', '=', $today)
                ->get();
        elseif($type == 'upcoming')
            $bookings = $bookings
                ->where('bookings.date', '>', $today)
                ->get();
        elseif($type == 'history')
            $bookings = $bookings
                ->where('bookings.date', '<', $today)
                ->get();

        // return the view with data required
        return view('booking.'.$userType.'.summary', compact('typeSelected', 'types', 'bookings'));
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
     * using id passed in to display the cancel booking form
     * contains details of the booking wanted to be cancelled
     * only accessible by authenticated users
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cancelBookingForm($id)
    {
        // redirect to login page if not authenticated
        if ( ! Auth::check() )
            return Redirect::to('/login');

        // retrieve booking according to id
        $booking = Booking::join('customers', 'bookings.customer_id', 'customers.customer_id')
            ->join('employees', 'employees.employee_id', 'bookings.employee_id')
            ->join('activities', 'employees.activity_id', 'activities.activity_id')
            ->join('businesses', 'businesses.business_id', 'bookings.business_id')
            ->find($id);

        return view('booking.cancel', compact('booking'));
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
     * showing the booking form for business owner to make booking for customer
     * only accessible by authenticated users
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function makeBooking()
    {
        // redirect to login page if not authenticated
        if ( ! Auth::check() )
            return Redirect::to('/login');

        // get user type
        $userType = Auth::user()['user_type'];

        // return view according to user type
        if( $userType == 'business')
            return view('booking.business.form');
        else
        {
            // retrieve all ready businesses from DB
            $businesses
                = Business::where('ready', true)
                ->get();

            return view('booking.customer.form' , compact('businesses'));
        }
    }

    /**
     * called when cancel booking form is submitted
     * request contains the booking id awaited to be cancelled
     * delete the booking from DB and redirect back to summary page
     *
     * @param Request $request
     * @return mixed
     */
    public function cancelBooking(Request $request)
    {
        // retrieve booking id from form submitted
        $bookingID = $request['booking'];

        // remove this booking from DB
        Booking::find($bookingID)->delete();

        // redirect back to booking summary
        return Redirect::to('/booking/summary');
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
                ->orderBy('bookings.start_time')
                ->get();
        }

        // get the business
        $business = Business::find($businessID);

        return view('booking.new', compact('request', 'employees', 'activities', 'bookings', 'business', 'error'));
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
        // get auth
        $auth = Auth::user();

        // retrieve booking with more details by joining tables
        $booking = Booking::join('employees', 'employees.employee_id', 'bookings.employee_id')
            ->join('activities', 'activities.activity_id', 'employees.activity_id')
            ->find($bookingSaved->booking_id);

        // retrieve business and customer from DB
        $business = Business::find($bookingSaved->business_id);
        $customer = Customer::find($bookingSaved->customer_id);

        return view('booking.confirmation', compact('business', 'customer', 'booking', 'auth'));
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
        // convert the start time to carbon and get string
        $carbonStartTime = new Carbon($data['time']);
        $startTime = $carbonStartTime->toTimeString();

        // get the activity from DB and num_of_slots
        $activity = Activity::find($data['service']);
        $numOfSlots = $activity->num_of_slots;

        // get the business from DB and slot_period
        $business = Business::find($data['business']);
        $slotPeriod = $business->slot_period;

        // get end time of the booking
        $carbonEndTime = $carbonStartTime->addMinute($numOfSlots * $slotPeriod);
        $endTime = $carbonEndTime->toTimeString();

        // get slot of same date, employee, for the time period, if exists
        $slot = Slot::join('bookings', 'bookings.booking_id', 'slots.booking_id')
            ->where('bookings.date', $data['date'])
            ->where('bookings.employee_id', $data['employee'])
            ->where('slots.slot_time', '>=', $startTime)
            ->where('slots.slot_time', '<', $endTime)
            ->get();

        // return if such slot already booked
        return (count($slot) == 0);
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

    /**
     * used in booking summary
     * return array of type and page titles
     *
     * @param $userType
     * @return array
     */
    private function getSummaryType($userType)
    {
        // initialise types for both customer and business owners
        $types = [
            [
                'type' => 'upcoming',
                'title' => 'Upcoming Bookings',
            ],
            [
                'type' => 'history',
                'title' => 'Booking History',
            ]
        ];

        // add types specifically for business owner only
        if($userType == 'business')
            $types = array_merge([
                [
                    'type' => 'recent',
                    'title' => 'Recent Bookings',
                ],
                [
                    'type' => 'today',
                    'title' => 'Today\'s Bookings',
                ],
            ], $types);

        return $types;
    }
}
