<?php

namespace App\Http\Controllers;


use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class EmployeeController
{
    public function newStaff(Request $request)
    {
        $businessID = $request['id'];
        return view('newstaff', compact('businessID'));
    }

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
            'role'          => 'required',
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
            'role'              => $data['role'],
            'available_days'    => $availability,
            'business_id'       => $data['business_id']
        ]);
    }
}