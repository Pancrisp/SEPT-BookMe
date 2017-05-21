<?php

namespace App\Http\Controllers;

use App\Business;
use App\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class DashboardController
{
    /**
     * load the dashboard
     * only authenticated user can access
     * get data needed according to user type
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function loadDashboard()
    {
        // redirect to login page if not authenticated
        if ( ! Auth::check() )
            return Redirect::to('/login');

        // get auth and details
        $auth = Auth::user();
		$id = $auth['foreign_id'];
        $type = $auth['user_type'];

        // get user according to user type, and return view dynamically
        if($type == 'customer')
        {
            $user = Customer::find($id);
            return view('dashboard.'.$type, compact('user'));
        }
        else
        {
            $user = Business::find($id);

            // check if this business has been fully set up
            if($user->ready)
                return view('dashboard.'.$type, compact('user'));
            else
                // if not set up, redirect to set up pages
                return Redirect::to('/business/hour/register');
        }
    }
}
