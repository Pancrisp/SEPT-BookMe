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

        // get user according to user type
        if($type == 'business')
            $user = Business::find($id);
        else
            $user = Customer::find($id);

        // return view dynamically
        return view('dashboard.'.$type, compact('user'));
    }
}
