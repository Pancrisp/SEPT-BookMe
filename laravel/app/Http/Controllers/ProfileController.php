<?php

namespace App\Http\Controllers;


use App\Business;
use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ProfileController
{
    /**
     * to display user profile
     * also allow user to update their details on the page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile()
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

        return view('profile.'.$userType, compact('user'));
    }

    /**
     * called when update profile form is submitted
     * validate incoming inputs according to user type
     * save to DB according to user type and id
     *
     * @param Request $request
     * @return mixed
     */
    public function updateProfile(Request $request)
    {
        // redirect to login page if not authenticated
        if ( ! Auth::check() )
            return Redirect::to('/login');

        // get auth and user id, and type
        $auth = Auth::user();
        $userID = $auth['foreign_id'];
        $userType = $auth['user_type'];

        // this validates the data
        $validator = $this->validator($request->all(), $userType);

        // when validation fails, redirect back with error messages
        if($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator);
        }

        // when validation passes, save to DB and redirect back with successful message
        $result = $this->update($request->all(), $userType, $userID);
        return Redirect::back()
            ->withErrors($result);
    }

    /**
     * validate incoming data for updating profile
     *
     * @param  array $data
     * @param $userType
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validator(array $data, $userType)
    {
        // general rules
        $rules = [
            'email'     => 'required|email|max:255',
            'phone'     => 'required|digits:10',
            'address'   => 'required'
        ];

        // add extra rule in case of business
        if($userType == 'business')
            $rules = array_merge($rules, [
                'business_name' => 'required|max:255'
            ]);

        // return validator according to rules set
        return Validator::make($data, $rules);
    }

    /**
     * update user profile if anything changed and save to DB
     * return result in array
     * contain type and message
     *
     * @param  array $data
     * @param $userType
     * @param $userID
     * @return array result
     */
    private function update(array $data, $userType, $userID)
    {
        // get respective user from DB
        if($userType == 'customer')
            $user = Customer::find($userID);
        else
            $user = Business::find($userID);

        // update and write message accordingly
        $result = [];

        // updating email
        if($user->email_address != $data['email'])
        {
            // validate email to be unique
            $validator = Validator::make($data, [
                'email' => 'unique:customers,email_address|unique:businesses,email_address'
            ]);

            // if validation fails, write error message
            if($validator->fails())
            {
                $result = array_merge($result, [
                    'email' => 'The email address you have entered is already registered.'
                ]);
            }

            // when validation passes, update email
            else
            {
                $user->email_address = $data['email'];
                $result = array_merge($result, [
                    'email' => 'Email address updated'
                ]);
            }
        }

        // updating mobile
        if($user->mobile_phone != $data['phone'])
        {
            $user->mobile_phone = $data['phone'];
            $result = array_merge($result, [
               'phone' => 'Mobile phone updated'
            ]);
        }

        // updating address
        if($user->address != $data['address'])
        {
            $user->address = $data['address'];
            $result = array_merge($result, [
                'address' => 'Address updated'
            ]);
        }

        // updating business name for business only
        if($userType == 'business' && $user->business_name != $data['business_name'])
        {
            // validate business name to be unique
            $validator = Validator::make($data, [
                'business_name' => 'unique:businesses'
            ]);

            // if validation fails, write error message
            if($validator->fails())
            {
                $result = array_merge($result, [
                    'business_name' => 'The business name has already been taken.'
                ]);
            }

            // when validation passes, update business name
            else
            {
                $user->business_name = $data['business_name'];
                $result = array_merge($result, [
                    'business_name' => 'Business name updated'
                ]);
            }
        }

        // if nothing has been changed
        if($result == [])
            $result = [
                'result' => 'No change made'
            ];

        // save to DB and return result
        $user->save();
        return $result;
    }
}