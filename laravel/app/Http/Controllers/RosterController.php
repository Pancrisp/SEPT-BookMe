<?php

namespace App\Http\Controllers;

use App\Activity;
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

        return view('roster.new', compact('employees'));
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

        // if staff can't be rostered on the date, redirect back with input and error messages
        if(!$this->canBeRostered($request->all())) {
            return Redirect::back()
                ->withInput()
                ->withErrors(['result' => 'Staff is not available on the date']);
        }

        // when validation passes, save to DB and redirect back with successful message
        if($this->create($request->all())){
            return Redirect::back()
                ->withErrors(['result' => 'Staff rostered successfully!']);
        }
    }

    /**
     * called when activity is selected in making booking
     * accessed by AJAX only
     * activityID and date are passed in request
     *
     * @param Request $request
     */
    public function getStaffByActivity(Request $request)
    {
        // defence 1st, make sure this is only accessible by AJAX request
        if( ! $request->ajax() )
            die;

        // get the roster on that date of this activity
        $roster = Roster::join('employees', 'employees.employee_id', 'rosters.employee_id')
            ->where('rosters.date', $request['date'])
            ->where('employees.activity_id', $request['activityID'])
            ->get();

        // pass result back to ajax by json
        print_r(json_encode($roster));
    }

    /**
     * show roster for the next 7 days
     * display based on date and activity
     * only accessible by business owner
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showRoster()
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

        // get dates of next 7 days from roster table of this business
        $dates = Roster::select('date')
            ->join('employees', 'employees.employee_id', 'rosters.employee_id')
            ->where('employees.business_id', $businessID)
            ->whereBetween('rosters.date', array($tomorrow->toDateString(), $aWeekLater->toDateString()))
            ->orderBy('rosters.date')
            ->groupBy('date')
            ->get();

        // get the day of each date and save to array
        foreach ($dates as $date)
        {
            $carbon = Carbon::parse($date['date']);
            $date['day'] = $carbon->format('l');
        }

        // get activities of this business
        $activities
            = Activity::where('business_id', $businessID)
            ->get();

        // loop through each activity of each date to get roster
        $rosters = [];
        foreach ($activities as $activity)
        {
            foreach ($dates as $date)
            {
                $rosters[$activity['activity_id']][$date['date']]
                    = Roster::join('employees', 'employees.employee_id', 'rosters.employee_id')
                    ->join('activities', 'activities.activity_id', 'employees.activity_id')
                    ->where('activities.activity_id', $activity['activity_id'])
                    ->where('rosters.date', $date['date'])
                    ->get();
            }
        }

        return view('roster.summary', compact('dates', 'activities', 'rosters'));
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
            'employee_id'   => 'required|numeric'
        ]);
    }

    /**
     * check if the staff is available on that day
     * also check if the staff has already been rostered on that date
     *
     * @param array $data
     * @return bool
     */
    private function canBeRostered(array $data)
    {
        // get employee availability and convert to an array
        $employee = Employee::find($data['employee_id']);
        $availability = $employee->available_days;
        $days = explode(' ', $availability);

        // get the day of input date
        $carbon = Carbon::parse($data['date']);
        $data['day'] = $carbon->format('l');

        // check if staff is available on that date
        $valid = false;
        foreach ($days as $day)
        {
            if ($day != "" && strpos($data['day'], $day) !== false)
            {
                $valid = true;
                break;
            }
        }

        // when available on that date
        if($valid)
        {
            // check if staff has already been rostered
            $roster = Roster::where('date', $data['date'])
                ->where('employee_id', $data['employee_id'])
                ->get();

            // set valid to false if such roster exists
            if(count($roster))
                $valid = false;
        }

        return $valid;
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
            'employee_id'   => $data['employee_id']
        ]);
    }
}
