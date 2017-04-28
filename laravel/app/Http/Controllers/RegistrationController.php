<?php

namespace App\Http\Controllers;

use App\Customer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class RegistrationController
{
    /**
     * This is called when submitting registration form
     * it validates the data
     *
     * when validation fails, redirect back to the page with input and error messages
     * when validation passes, create the user in DB and redirect to login page
     *
     * @param Request $request
     * @return Redirect
     */
    public function register(Request $request)
    {
        // this validate the data from request
        $validator = $this->validator($request->all());

        // when validation fails, redirect back to the page with input and error messages
        if($validator->fails()) {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator);
        }

        // when validation passes, create the user in DB and redirect to login page
        if($this->create($request->all())){
            return redirect('/');
        }
    }

    /**
     * validate incoming data for creating a new account for customer
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validator(array $data)
    {
        return Validator::make($data, [
            'fullname'  => 'required|max:255',
            'username'  => 'required|max:255|unique:customers|unique:businesses',
            'password'  => 'required|min:6|confirmed',
            'email'     => 'required|email|max:255|unique:customers,email_address|unique:businesses,email_address',
            'phone'     => 'required|digits:10',
            'address'   => 'required'
        ]);
    }

    /**
     * create a customer once validation passed
     * and save to DB
     *
     * @param  array  $data
     * @return boolean
     */
    private function create(array $data)
    {
        // create the customer
        $customer = Customer::create([
            'customer_name' => $data['fullname'],
            'username'      => $data['username'],
            'email_address' => $data['email'],
            'mobile_phone'  => $data['phone'],
            'address'       => $data['address']
        ]);

        // create the user saving foreign key, this is for authentication
        User::create([
            'email'     => $data['email'],
            'password'  => bcrypt($data['password']),
            'user_type' => 'customer',
            'foreign_id' => $customer->customer_id
        ]);

        return true;
    }
}
