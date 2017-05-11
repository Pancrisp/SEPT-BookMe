<?php

namespace App\Http\Controllers;


use App\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ActivityController
{
    public function businessActivity()
    {

    }

    public function registerBusinessActivityForm()
    {
        // redirect to login page if not authenticated, or incorrect user type
        if ( ! Auth::check() || Auth::user()['user_type'] != 'business')
            return Redirect::to('/login');

        // get auth and business ID
        $auth = Auth::user();
        $businessID = $auth['foreign_id'];

        // get business and slot period from DB
        $business = Business::find($businessID);
        $slotPeriod = $business->slot_period;

        return view('business.setup.activity', compact('business' , 'slotPeriod'));
    }

    public function registerBusinessActivity(Request $request, $action)
    {

    }
}