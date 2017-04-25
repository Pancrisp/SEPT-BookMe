<?php

namespace App\Http\Controllers;


use App\Activity;
use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class EmployeeController
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addStaffForm(Request $request)
    {
        // Checking if the session is set and business ID is set
        if (! $request->session()->has('user') || ! isset($request['id'])) { return Redirect::to('/'); }

        $businessID = $request['id'];

        $typeOfActivities
            = Activity::where('business_id', $businessID)
            ->get();

        return view('newStaff', compact('businessID', 'typeOfActivities'));
    }

    /**
     * @param Request $request
     * @return Redirect
     */
    public function addStaff(Request $request)
    {
        $validator = $this->registrationValidator($request->all());

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
     * view staff summary
     * includes name, TFN, contact, activity, available days
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewStaffSummary(Request $request)
    {
        // Checking if the session is set and business ID is set
        if (! $request->session()->has('user') || ! isset($request['id'])) { return Redirect::to('/'); }

        $businessID = $request['id'];

        $employees = Employee::join('activities', 'employees.activity_id', 'activities.activity_id')
            ->where('employees.business_id', $businessID)
            ->get();

        return view('staffSummary', compact('businessID', 'employees'));
    }

    /**
     * show staff update form
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showStaffUpdateForm(Request $request)
    {
        // Checking if the session is set and business ID is set
        if (! $request->session()->has('user') || ! isset($request['id'])) { return Redirect::to('/'); }

        $businessID = $request['id'];

        $employees =
            Employee::where('business_id', $businessID)
            ->get();

        return view('updateStaff', compact('businessID', 'employees'));
    }

    /**
     * update staff working days
     *
     * @param Request $request
     * @return Redirect
     */
    public function updateStaffAvailableDays(Request $request)
    {
        $validator = $this->updateValidator($request->all());

        if($validator->fails()) {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator);
        }

        if($this->update($request->all())){
            return Redirect::back()
                ->withErrors(['result' => 'Staff working days updated successfully!']);
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

    /**
     * Get a validator for an incoming registration request.
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
