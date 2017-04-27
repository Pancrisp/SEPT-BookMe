<?php

namespace App\Http\Controllers;

use App\Business;
use App\Customer;
use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class DashboardController
{
    private $user;

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
            // if successfully authenticated
            if(Auth::attempt(['email' => $email, 'password' => $data['password']]))
            {
                // get the user and user_type
                $user = Auth::user();
                $type = $user['user_type'];

                // redirect according to the type of user
                if($type == 'business')
                {
                    $id = $user['foreign_id'];
                    $business = Business::find($id);

                    return view($type.'Dashboard', compact('business'));
                }
                else
                    return $this->customerDashboard($user);
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

    public function backToDashboard(Request $request)
    {
        // Security check: if the session is set and business ID is set
        if (! $request->session()->has('user') || ! isset($request['id'])) { return Redirect::to('/'); }

		$id = $request['id'];
        $type = $request['type'];

        if($type == 'business')
        {
            $user = Business::find($id);
        }
        else
        {
            $user = Customer::find($id);
        }

		return view($type.'Dashboard', compact('user'));
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

    /**
     * Check if the username entered exists in the database
     *
     * @param  array  $data
     * @return boolean
     */
    private function authenticated(array $data)
    {
        $type = $data['usertype'];

        if($type == 'customer')
        {
            $customer = Customer::where('username', $data['username'])
                ->orWhere('email_address', $data['username'])
                ->first();

            $this->user = $customer;

            return password_verify($data['password'], $customer->password);
        }
        else
        {
            $business = Business::where('username', $data['username'])
                ->orWhere('email_address', $data['username'])
                ->first();

            $this->user = $business;

            return password_verify($data['password'], $business->password);
        }
    }

    /**
     * Build data needed for customer dashboard and return view
     */
    private function customerDashboard($user){

        $timeSlots = [
            '09:00',
            '09:30',
            '10:00',
            '10:30',
            '11:00',
            '11:30',
            '12:00',
            '12:30',
            '13:00',
            '13:30',
            '14:00',
            '14:30',
            '15:00',
            '15:30',
            '16:00',
            '16:30'
        ];

        $businesses = Business::all();
        $employees = Employee::all();

        return view('customerDashboard', compact('user', 'timeSlots', 'businesses', 'employees'));
    }
}
