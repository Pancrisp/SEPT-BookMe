<?php

namespace App\Http\Controllers;


use App\Business;
use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AuthenticationController
{
    /**
     * This is called when login form is submitted
     * It checks if the user exists
     * Then check if password is correct
     *
     * when successfully authenticated, redirect to dashboard according to user type
     * otherwise, redirect back with input and appropriate error message
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login(Request $request)
    {
        // get all data from request sent
        $data = $request->all();

        // get the email of the user, for authentication later
        $email = $this->getUserEmail($data);

        // when user is not found, email will be empty string
        if(!$email)
        {
            // redirect back with input and error message
            return Redirect::back()
                ->withInput()
                ->withErrors(array('username' => 'Account not found.'));
        }

        // when user is found
        else
        {
            // if successfully authenticated, redirect to dashboard
            if(Auth::attempt(['email' => $email, 'password' => $data['password']]))
            {
                return Redirect::to('/');
            }

            // when authentication fails
            else
            {
                // redirect back with input and error message
                return Redirect::back()
                    ->withInput()
                    ->withErrors(array('password' => 'Incorrect password. Please try again!'));
            }
        }
    }

    /**
     * log user out and redirect to login page
     *
     * @return Redirect
     */
    public function logout()
    {
        Auth::logout();

        return Redirect::to('/login');
    }

    /**
     * Get user's email based on username entered
     * username can be either username or email
     * both are unique
     *
     * @param  array  $data
     * @return string
     */
    private function getUserEmail(array $data)
    {
        // username can be username or email
        $username = $data['username'];

        // use the input to find possible customer
        $customer = Customer::where('username', $username)
            ->OrWhere('email_address', $username)
            ->first();

        // use the input to find possible business
        $business = Business::where('username', $username)
            ->OrWhere('email_address', $username)
            ->first();

        // initialise empty email
        $email = '';

        // when such customer found, get the email address
        if($customer)
            $email = $customer->email_address;

        // when such business found, get the email address
        elseif($business)
            $email = $business->email_address;

        return $email;
    }
}