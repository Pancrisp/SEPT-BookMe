<?php

namespace App\Http\Controllers;

use App\Business;
use App\Customer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class RegistrationController
{
    /**
     * user type is passed in from url
     * return registration form according to user type
     *
     * @param $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function loadRegistrationForm($type)
    {
        return view('registration.'.$type.'.new');
    }

    /**
     * This is called when submitting registration form
     * it validates the data
     * user type is passed in url
     *
     * when validation fails, redirect back to the page with input and error messages
     * when validation passes, create the user in DB and redirect to login page
     *
     * @param Request $request
     * @param $type
     * @return Redirect
     */
    public function register(Request $request, $type)
    {
        // this validate the data from request
        $validator = $this->validator($request->all(), $type);

        // when validation fails, redirect back to the page with input and error messages
        if($validator->fails()) {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator);
        }

        // when validation passes, create the user in DB and redirect to login page
        if($this->create($request->all(), $type)){
            return redirect('/');
        }
    }

    /**
     * validate incoming data for creating a new account
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validator(array $data, $type)
    {
        // general rules
        $generalRules = [
            'username'  => 'required|max:255|unique:customers|unique:businesses',
            'password'  => 'required|min:6|confirmed',
            'email'     => 'required|email|max:255|unique:customers,email_address|unique:businesses,email_address',
            'phone'     => 'required|digits:10',
            'address'   => 'required'
        ];

        // custom rules for different type of users
        $customRules = [];
        if($type == 'customer')
            $customRules = [
                'fullname'  => 'required|max:255',
            ];
        elseif($type == 'business')
            $customRules = [
                'business_name' => 'required|max:255|unique:businesses',
                'owner_name'    => 'required|max:255|unique:businesses',
            ];

        // combine all rules by array_merge
        $rules = array_merge($generalRules, $customRules);

        // return validator according to rules set
        return Validator::make($data, $rules);
    }

    /**
     * create a customer once validation passed
     * and save to DB
     *
     * @param  array  $data
     * @return boolean
     */
    private function create(array $data, $type)
    {
        // initialise user id
        $userID = -1;

        // create the customer
        if($type == 'customer')
        {
            $customer = Customer::create([
                'customer_name' => $data['fullname'],
                'username'      => $data['username'],
                'email_address' => $data['email'],
                'mobile_phone'  => $data['phone'],
                'address'       => $data['address']
            ]);

            $userID = $customer->customer_id;
        }

        // create the business
        elseif($type == 'business')
        {
            $business = Business::create([
                'business_name' => $data['business_name'],
                'owner_name'    => $data['owner_name'],
                'username'      => $data['username'],
                'email_address' => $data['email'],
                'mobile_phone'  => $data['phone'],
                'address'       => $data['address']
            ]);

            $userID = $business->business_id;
        }

        // when userID is valid, create the user saving foreign key, this is for authentication
        if($userID != -1)
        {
            User::create([
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'user_type' => $type,
                'foreign_id' => $userID
            ]);

            return true;
        }

        return false;
    }
}
