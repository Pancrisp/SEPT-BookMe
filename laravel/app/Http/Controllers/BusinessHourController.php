<?php

namespace App\Http\Controllers;


use App\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class BusinessHourController
{
    public function businessHour()
    {

    }

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
        $days = $this->getDays();

        return view('business.setup.openingHour', compact('business', 'days'));
    }

    public function registerBusinessHour(Request $request)
    {

    }

    public function updateBusinessHour()
    {

    }

    private function registerValidator(array $data)
    {

    }

    private function updateValidator(array $data)
    {

    }

    private function save(array $data)
    {

    }

    private function update(array $data)
    {

    }

    private function getDays()
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

        return $days;
    }
}