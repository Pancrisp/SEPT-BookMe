<?php

namespace App\Http\Controllers;

use App\Roster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class RosterController
{
    public function addRoster(Request $request)
    {
        $validator = $this->validator($request->all());

        if($validator->fails()) {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator);
        }

        if($this->create($request->all())){
            return Redirect::back()
                ->withErrors(['result' => 'Roster added successfully!']);
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
            'fullname'  => 'required|max:255',
            'role'      => 'required',
            'date'      => 'required|date|after:today|before:'.Carbon::now()->addDays(30),
            'shift'     => 'required'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return Roster
     */
    private function create(array $data)
    {
        return Roster::create([
            'date'          => $data['date'],
            'shift'         => $data['shift'],
            'employee_id'   => $data['employee_id']
        ]);
    }
}