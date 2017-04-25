<?php

namespace App\Http\Controllers;


use App\Activity;
use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class EmployeeController
{
    public function newStaff(Request $request)
    {
        // Checking session
        if ($request->session()->has('user')) {

            $businessID = $request['id'];

            $typeOfActivities
                = Activity::where('business_id', $businessID)
                ->get();

            return view('newstaff', compact('businessID', 'typeOfActivities'));
        }
        else
            return Redirect::to('/');
    }

    /**
     * @param Request $request
     * @return Redirect
     */
    public function addStaff(Request $request)
    {
        $validator = $this->validator($request->all());

        if($validator->fails()) {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator);
        }

        if($this->create($request->all())){
            return Redirect::back()
                ->withErrors(['result' => 'Staff added successfully!']);
        }
    }

    /**
     * called by ajax only
     * to return availability of a certain employee by empID
     *
     * @param Request $request
     */
    Public function getAvailability(Request $request)
    {
        // defence 1st, make sure this is only accessible by AJAX request
        if( ! $request->ajax() ) { die; }

        // get employee from db by empID and get working days
        $employee = Employee::find($request['empID']);
        $availability = $employee->available_days;

        // pass result back to ajax by json
        print_r(json_encode($availability));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validator(array $data)
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
     * Create a new user instance after a valid registration.
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
}
