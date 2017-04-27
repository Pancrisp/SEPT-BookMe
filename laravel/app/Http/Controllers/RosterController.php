<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Roster;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class RosterController
{
    /**
     * This is to display the add new roster form
     * only accessible by business owner
     *
     * get the employees of this business and return to view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addRosterForm()
    {
	    // redirect to login page if not authenticated, or incorrect user type
        if ( ! Auth::check() || Auth::user()['user_type'] != 'business')
            return Redirect::to('/login');

        // get auth and business ID
        $auth = Auth::user();
        $businessID = $auth['foreign_id'];

        // get employees of this business
        $employees
            = Employee::where('business_id', $businessID)
            ->get();

        return view('newRoster', compact('employees'));
    }

    /**
     * This is called when submitting add new roster form
     * it validates the data
     *
     * if validation fails, redirect back with input and error messages
     * if validation passes, save to DB and redirect back with successful message
     *
     * @param Request $request
     * @return Redirect
     */
    public function addRoster(Request $request)
    {
        // this validates the data
        $validator = $this->validator($request->all());

        // when validation fails, redirect back with input and error messages
        if($validator->fails()) {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator);
        }

        // when validation passes, save to DB and redirect back with successful message
        if($this->update($request->all()) || $this->create($request->all())){
            return Redirect::back()
                ->withErrors(['result' => 'Roster added successfully!']);
        }
    }

    /**
     * This is deprecated
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showRoster(Request $request)
    {
        // redirect to login page if not authenticated, or incorrect user type
        if ( ! Auth::check() || Auth::user()['user_type'] != 'business')
            return Redirect::to('/login');

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
    }

    /**
     * validate incoming data for creating a new roster
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
     * this might be deprecated
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
     * create a new roster when validation passed
     * and save to DB
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
