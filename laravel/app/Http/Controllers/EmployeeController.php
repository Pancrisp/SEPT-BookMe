<?php

namespace App\Http\Controllers;


use App\Activity;
use App\Booking;
use App\Business;
use App\Employee;
use App\Roster;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class EmployeeController
{
    /**
     * This display the add new staff form
     * only accessible by business owner
     *
     * get activities from DB and pass to view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addStaffForm()
    {
        // redirect to login page if not authenticated, or incorrect user type
        if ( ! Auth::check() || Auth::user()['user_type'] != 'business')
            return Redirect::to('/login');

        // get auth and business ID
        $auth = Auth::user();
        $businessID = $auth['foreign_id'];

        // get activities of this business
        $activities
            = Activity::where('business_id', $businessID)
            ->get();

        return view('staff.new', compact('activities', 'businessID'));
    }

    /**
     * This is called when submitting add new staff form
     * it validates the data
     *
     * if validation fails, redirect back with input and error messages
     * if validation passes, save to DB and redirect back with successful message
     *
     * @param Request $request
     * @return Redirect
     */
    public function addStaff(Request $request)
    {
        // this validates the data
        $validator = $this->registrationValidator($request->all());

        // when validation fails, redirect back with input and error messages
        if($validator->fails()) {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator);
        }

        // when validation passes, save to DB and redirect back with successful message
        if($this->create($request->all())){
            return Redirect::back()
                ->withErrors(['result' => 'Staff added successfully!']);
        }
    }

    /**
     * view staff summary
     * includes name, TFN, contact, activity, available days
     * only accessible by business owner
     *
     * get employees and their activities from DB and return to view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewStaffSummary()
    {
        // redirect to login page if not authenticated, or incorrect user type
        if ( ! Auth::check() || Auth::user()['user_type'] != 'business')
            return Redirect::to('/login');

        // get auth and business ID
        $auth = Auth::user();
        $businessID = $auth['foreign_id'];

        // get employees and their activities of this business
        $employees = Employee::join('activities', 'employees.activity_id', 'activities.activity_id')
            ->where('employees.business_id', $businessID)
            ->get();

        return view('staff.summary', compact('employees'));
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
            ->orderBy('rosters.date')
            ->groupBy('rosters.date')
            ->get();

        // get date selected from request, if not set, by default tomorrow
        $dateSelected = isset($request['date'])? $request['date']: $tomorrow->toDateString();

        // get employees rostered on the date selected
        $employees = Employee::join('rosters', 'rosters.employee_id', 'employees.employee_id')
            ->join('activities', 'activities.activity_id', 'employees.activity_id')
            ->where('employees.business_id', $businessID)
            ->where('rosters.date', $dateSelected)
            ->orderBy('employees.activity_id')
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
                ->orderBy('bookings.start_time')
                ->get();
        }

        return view('staff.availability', compact('bookings', 'employees', 'dates', 'business', 'dateSelected'));
    }

    /**
     * show staff update form
     * only accessible by business owner
     *
     * get employees from DB and return to view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showStaffUpdateForm()
    {
        // redirect to login page if not authenticated, or incorrect user type
        if ( ! Auth::check() || Auth::user()['user_type'] != 'business')
            return Redirect::to('/login');

        // get auth and business ID
        $auth = Auth::user();
        $businessID = $auth['foreign_id'];

        // get employees of this business
        $employees =
            Employee::where('business_id', $businessID)
            ->get();

        return view('staff.update', compact('employees', 'businessID'));
    }

    /**
     * This is called when submitting update staff info form
     * update staff working days
     * only accessible by business owner
     *
     * if validation fails, redirect back with input and error messages
     * if validation passes, save to DB and redirect back with successful message
     *
     * @param Request $request
     * @return Redirect
     */
    public function updateStaffAvailableDays(Request $request)
    {
        // this validates the data
        $validator = $this->updateValidator($request->all());

        // when validation fails, redirect back with input and error messages
        if($validator->fails()) {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator);
        }

        // when validation passes, save to DB and redirect back with successful message
        if($this->update($request->all())){
            return Redirect::back()
                ->withErrors(['result' => 'Staff working days updated successfully!']);
        }
    }

    /**
     * called by ajax only
     * return availability and activity of a certain employee by empID
     *
     * @param Request $request
     */
    Public function getEmployeeDetails(Request $request)
    {
        // defence 1st, make sure this is only accessible by AJAX request
        if( ! $request->ajax() ) { die; }

        // get employee from db by empID
        $employee = Employee::join('activities', 'employees.activity_id', 'activities.activity_id')
            ->find($request['empID']);

        // pass result back to ajax by json
        print_r(json_encode($employee));
    }

    /**
     * validate incoming data for adding new staff
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function registrationValidator(array $data)
    {
        return Validator::make($data, [
            'fullname'      => 'required|max:255',
            'taxfileno'     => 'required|digits:9|unique:employees,TFN',
            'phone'         => 'required|digits:10',
            'activity'      => 'required',
            'availability'  => 'required'
        ]);
    }

    /**
     * create the employee once validation passed
     * and save to DB
     *
     * @param  array  $data
     * @return Employee
     */
    private function create(array $data)
    {
        $availability = "";

        foreach ($data['availability'] as $day)
        {
            $availability = $availability . " ". $day;
        }

        return Employee::create([
            'employee_name'     => $data['fullname'],
            'TFN'               => $data['taxfileno'],
            'mobile_phone'      => $data['phone'],
            'activity_id'       => $data['activity'],
            'available_days'    => $availability,
            'business_id'       => $data['business_id']
        ]);
    }

    /**
     * validate incoming data for updating staff availability
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function updateValidator(array $data)
    {
        return Validator::make($data, [
            'employee_id'   => 'required',
            'availability'  => 'required'
        ]);
    }

    /**
     * update staff working days by employee_id
     * and save to DB
     *
     * @param  array  $data
     * @return Employee
     */
    private function update(array $data)
    {
        $employee = Employee::find($data['employee_id']);

        $availability = "";
        foreach ($data['availability'] as $day)
        {
            $availability = $availability . " ". $day;
        }
        $employee->available_days = $availability;

        return $employee->save();
    }
}
