<?php

namespace App\Http\Controllers;


use App\Business;
use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProfileController
{
    /**
     * to display user profile depending on user type
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile($type)
    {
        // redirect to login page if not authenticated
        if ( ! Auth::check() )
            return Redirect::to('/login');

        // get auth and user id, and type
        $auth = Auth::user();
        $userID = $auth['foreign_id'];
        $userType = $auth['user_type'];

        // get respective user from DB
        if($userType == 'customer')
            $user = Customer::find($userID);
        else
            $user = Business::find($userID);

        return view('profile.'.$userType.'.'.$type, compact('user'));
    }

    public function update(Request $request)
    {

    }
}