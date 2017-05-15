<?php

namespace App\Http\Controllers;


use App\Business;
use App\BusinessHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class BusinessHourController
{
    public function businessHour()
    {

    }

    /**
     * display the business hour registration form
     * when a business is registered but not ready yet
     * only accessible by business owner
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function registerBusinessHourForm()
    {
        // redirect to login page if not authenticated, or incorrect user type
        if ( ! Auth::check() || Auth::user()['user_type'] != 'business')
            return Redirect::to('/login');

        // get auth and business ID
        $auth = Auth::user();
        $businessID = $auth['foreign_id'];

        // get business from DB
        $business = Business::find($businessID);

        // get days array
        $days = $this->getDays('full');

        return view('business.setup.openingHour', compact('business', 'days'));
    }

    /**
     * called when register business hour form is submitted
     * validate incoming data
     * redirect back when validation fails
     * save to DB and redirect to next registration page otherwise
     *
     * @param Request $request
     * @return Redirect
     */
    public function registerBusinessHour(Request $request)
    {
        // this validates the data
        $validator = $this->registerValidator($request->all());

        // when validation fails, redirect back with input and error messages
        if(!$validator['valid']) {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator['message']);
        }

        // redirect to activity registration after saving to DB
        if($this->save($request->all())) {
            return Redirect::to('/business/activity/register');
        }
    }

    public function updateBusinessHour()
    {

    }

    /**
     * validate incoming data when registering opening hours for business
     * closing time has to be after opening time
     *
     * @param array $data
     * @return array $validator
     */
    private function registerValidator(array $data)
    {
        // initialising validator
        $validator['valid'] = true;
        $validator['message'] = [];

        // to check closing time is after opening time
        if ($data['closing_time_all'] < $data['opening_time_all'])
        {
            $validator['valid'] = false;
            $validator['message']['opening_hour_all'] = 'The closing time should be later than the opening time';
        }

        // if any special days is selected
        if(isset($data['special_days']))
        {
            // to get short days and special days
            $days = $this->getDays('short');
            $specialDays = $data['special_days'];

            // check through all selected closing time is after opening time
            foreach ($days as $day)
                if(in_array($day, $specialDays) && $data['closing_time_'.$day] < $data['opening_time_'.$day])
                {
                    $validator['valid'] = false;
                    $validator['message']['opening_hour_'.$day] = 'The closing time should be later than the opening time';
                }
        }

        return $validator;
    }

    private function updateValidator(array $data)
    {

    }

    /**
     * save business hours accordingly
     * specifically for special days
     * generally for all other days
     *
     * @param array $data
     * @return bool
     */
    private function save(array $data)
    {
        // get auth and business ID
        $auth = Auth::user();
        $businessID = $auth['foreign_id'];

        // get days
        $days = $this->getDays('short');
        $specialDays = isset($data['special_days'])? $data['special_days']: [];
        $normalDays = array_diff($days, $specialDays);

        // save those special days
        foreach($specialDays as $day)
        {
            BusinessHour::create([
                'business_id'   => $businessID,
                'day'           => $day,
                'opening_time'  => $data['opening_time_'.$day],
                'closing_time'  => $data['closing_time_'.$day]
            ]);
        }

        // save normal days
        foreach($normalDays as $day)
        {
            BusinessHour::create([
                'business_id'   => $businessID,
                'day'           => $day,
                'opening_time'  => $data['opening_time_all'],
                'closing_time'  => $data['closing_time_all']
            ]);
        }

        // get all the instance created, and check if it's 7
        $businessHours
            = BusinessHour::where('business_id', $businessID)
            ->get();

        return count($businessHours) == 7;
    }

    private function update(array $data)
    {

    }

    private function getDays($type)
    {
        $days = [
            [
                'short' => 'Mon',
                'full'  => 'Monday'
            ],
            [
                'short' => 'Tue',
                'full'  => 'Tuesday'
            ],
            [
                'short' => 'Wed',
                'full'  => 'Wednesday'
            ],
            [
                'short' => 'Thu',
                'full'  => 'Thursday'
            ],
            [
                'short' => 'Fri',
                'full'  => 'Friday'
            ],
            [
                'short' => 'Sat',
                'full'  => 'Saturday'
            ],
            [
                'short' => 'Sun',
                'full'  => 'Sunday'
            ],
        ];

        if($type == 'full')
            return $days;
        else
        {
            $shortDays = [];
            foreach ($days as $day)
                array_push($shortDays, $day['short']);

            return $shortDays;
        }
    }
}