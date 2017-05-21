<?php

namespace App\Http\Controllers;


use App\Business;
use App\BusinessHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class BusinessHourController
{
    /**
     * display business hours where update is possible
     * only accessible by business owner
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function businessHour()
    {
        // redirect to login page if not authenticated, or incorrect user type
        if ( ! Auth::check() || Auth::user()['user_type'] != 'business')
            return Redirect::to('/login');

        // get auth and business ID
        $auth = Auth::user();
        $businessID = $auth['foreign_id'];

        // get full days
        $days = $this->getDays('full');

        // get all business hours of this business
        foreach($days as $shortDay => $longDay)
            $businessHours[$shortDay]
                = BusinessHour::where('business_id', $businessID)
                ->where('day', $shortDay)
                ->first();

        return view('business.display.openingHour', compact('days', 'businessHours'));
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

        return view('business.register.openingHour', compact('business', 'days'));
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

    /**
     * called when update form is submitted
     * validate incoming input
     * take an extra input from url: the day, or all
     * redirect back when successfully updated
     *
     * @param Request $request
     * @param $day
     * @return mixed
     */
    public function updateBusinessHour(Request $request, $day)
    {
        // save the url day into request
        $request['url_day'] = $day;

        // get auth and business ID
        $auth = Auth::user();
        $businessID = $auth['foreign_id'];
        $request['business_id'] = $businessID;

        // this validates the data
        $validator = $this->updateValidator($request->all());

        // when validation fails, redirect back with input and error messages
        if(!$validator['valid']) {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator['message']);
        }

        // redirect back after update DB
        if($this->update($request->all())) {
            return Redirect::back();
        }
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

    /**
     * validate if closing time is after opening time
     *
     * @param array $data
     * @return mixed
     */
    private function updateValidator(array $data)
    {
        // initialising validator
        $validator['valid'] = true;
        $validator['message'] = [];

        // retrieve day submitted in the form
        $submittedDay = $data['day'];

        // to check closing time is after opening time
        if ($data['closing_time_'.$submittedDay] < $data['opening_time_'.$submittedDay])
        {
            $validator['valid'] = false;
            $validator['message']['opening_hour_'.$submittedDay] = 'The closing time should be later than the opening time';
        }

        return $validator;
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

    /**
     * update DB according to day input in url
     *
     * @param array $data
     * @return bool
     */
    private function update(array $data)
    {
        // get data needed
        $submittedDay = $data['day'];
        $urlDay = $data['url_day'];
        $businessID = $data['business_id'];
        $openingTime = $data['opening_time_'.$submittedDay];
        $closingTime = $data['closing_time_'.$submittedDay];

        // if not update all
        if($urlDay != 'all')
        {
            // get specific business hour from DB
            $businessHour
                = BusinessHour::where('business_id', $businessID)
                ->where('day', $submittedDay)
                ->first();

            // update accordingly and save
            $businessHour->opening_time = $openingTime;
            $businessHour->closing_time = $closingTime;
            $businessHour->save();
        }

        // if update all
        else
        {
            // get all business hours for this business from DB
            $businessHours
                = BusinessHour::where('business_id', $businessID)
                ->get();

            // update each one and save
            foreach($businessHours as $businessHour)
            {
                $businessHour->opening_time = $openingTime;
                $businessHour->closing_time = $closingTime;
                $businessHour->save();
            }
        }

        return true;
    }

    /**
     * return an array of days
     *
     * @param $type
     * @return array
     */
    private function getDays($type)
    {
        $days = [
            'Mon' => 'Monday',
            'Tue' => 'Tuesday',
            'Wed' => 'Wednesday',
            'Thu' => 'Thursday',
            'Fri' => 'Friday',
            'Sat' => 'Saturday',
            'Sun' => 'Sunday'
        ];

        if($type == 'full')
            return $days;
        else
        {
            $shortDays = [];
            foreach ($days as $key => $value)
                array_push($shortDays, $key);

            return $shortDays;
        }
    }
}