<?php

namespace App\Http\Controllers;

use App\Roster;
use Carbon\Carbon;
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

    public function showRoster(Request $request)
    {
        $businessID = $request['business_id'];

        $today = Carbon::now()->toDateString();
        $aWeeklater = $today->addWeek();

        $rosters = Roster::join('employees', 'employees.employee_id', 'rosters.employee_id')
            ->select(
                'employees.employee_name AS name',
                'rosters.date AS date',
                'rosters.shift AS shift'
                )
            ->where('employees.business_id', $businessID)
            ->whereBetween('date', array($today, $aWeeklater))
            ->orderBy('date', 'asc')
            ->get();

        foreach ($rosters as $roster)
        {
            $date = Carbon::parse($roster['date']);
            $roster['day'] = $date->format('l');
        }

        return view('showRoster', compact('rosters'));
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
            'date'          => 'required|date|after:today|before:'.Carbon::now()->addDays(30),
            'shift'         => 'required',
            'employee_id'   => 'required|numeric'
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