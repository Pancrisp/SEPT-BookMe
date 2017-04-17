<?php

namespace App\Http\Controllers;

use App\Business;
use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DashboardController
{
    private $user;

    public function login(Request $request)
    {
        $data = $request->all();
        $data['usertype'] = $this->getUserType($data);

        if($data["usertype"]==null)
        {
            return Redirect::back()
                ->withInput()
                ->withErrors(array('username' => 'Account not found.'));
        }
        elseif(!$this->authenticated($data))
        {
            return Redirect::back()
                ->withInput()
                ->withErrors(array('password' => 'Incorrect password. Please try again!'));
        }
        else
        {
            $type = $data['usertype'];
            $user = $this->user;

            if($type == 'business')
                return view($type.'Dashboard', compact('user'));
            else
                return $this->customerDashboard($user);
        }
    }

    public function backToDashboard(Request $request)
    {
        if(! isset($request['id'])) { return Redirect::to('/'); }

        $businessID = $request['id'];
        $user = Business::where('business_id', $businessID)
            ->first();

        return view('businessDashboard', compact('user'));
    }

    /**
     * Get user type based on te username entered
     *
     * @param  array  $data
     * @return string
     */
    private function getUserType(array $data)
    {
        $customerUsernames  = Customer::select('username')->get()->toArray();
        $customerEmails     = Customer::select('email_address')->get()->toArray();

        $businessUsernames  = Business::select('username')->get()->toArray();
        $businessEmails     = Business::select('email_address')->get()->toArray();

        if  (   in_array(['username' => $data['username']], $customerUsernames)
            ||  in_array(['email_address' => $data['username']], $customerEmails)
            )
        {
            return "customer";
        }
        else if (   in_array(['username' => $data['username']], $businessUsernames)
                ||  in_array(['email_address' => $data['username']], $businessEmails)
                )
        {
            return "business";
        }
        else
        {
            return null;
        }
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

        return view('customerDashboard', compact('user', 'timeSlots', 'businesses'));

    }
}