<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Roster;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class RosterController
{
    public function newRoster(Request $request)
    {
	// Checking if the session is set
        if ($request->session()->has('user')) {
		$businessID = $request['id'];
		$employees = Employee::where('business_id', $businessID)->get();
		return view('newRoster', compact('employees', 'businessID'));
	}else
		return Redirect::to('/');
    }

    public function addRoster(Request $request)
    {
        $validator = $this->validator($request->all());

        if($validator->fails()) {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator);
        }

        if($this->update($request->all()) || $this->create($request->all())){
            return Redirect::back()
                ->withErrors(['result' => 'Roster added successfully!']);
        }
    }

    public function showRoster(Request $request)
    {
	
	// Checking if the session is set
        if ($request->session()->has('user')) {        
		$businessID = $request['id'];

		$tomorrow = Carbon::now()->addDay();
		$aWeeklater = Carbon::now()->addWeek();

		$dayShifts = Roster::join('employees', 'employees.employee_id', 'rosters.employee_id')
		    ->select(
		        'employees.employee_name AS name',
		        'rosters.date AS date',
		        'rosters.shift AS shift'
		    )
		    ->where('employees.business_id', $businessID)
		    ->whereBetween('date', array($tomorrow->toDateString(), $aWeeklater->toDateString()))
		    ->orderBy('date', 'asc')
		    ->where('shift', 'Day')
		    ->get();

		foreach ($dayShifts as $roster)
		{
		    $date = Carbon::parse($roster['date']);
		    $roster['day'] = $date->format('l');
		}

		$nightShifts = Roster::join('employees', 'employees.employee_id', 'rosters.employee_id')
		    ->select(
		        'employees.employee_name AS name',
		        'rosters.date AS date',
		        'rosters.shift AS shift'
		    )
		    ->where('employees.business_id', $businessID)
		    ->whereBetween('date', array($tomorrow->toDateString(), $aWeeklater->toDateString()))
		    ->orderBy('date', 'asc')
		    ->where('shift', 'Night')
		    ->get();

		foreach ($nightShifts as $roster)
		{
		    $date = Carbon::parse($roster['date']);
		    $roster['day'] = $date->format('l');
		}

		return view('showRoster', compact('dayShifts', 'nightShifts', 'businessID'));
	}else
		return Redirect::to('/');
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
            'date'          => 'required|date|after:today',
            'shift'         => 'required',
            'employee_id'   => 'required|numeric'
        ]);
    }

    /**
     * Replace the employee_id if there was someone in the shift
     *
     * @return boolean
     */
    private function update(array $data)
    {
        $roster = Roster::where('date', $data['date'])
            ->where('shift', $data['shift'])
            ->first();

        if($roster != null)
        {
            $roster->employee_id = $data['employee_id'];
            $roster->save();

            return true;
        }
        else
            return false;
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
