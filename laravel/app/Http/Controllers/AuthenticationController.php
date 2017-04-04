<?php

namespace App\Http\Controllers;

use App\Business;
use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AuthenticationController
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
            $user = $this->user->toArray();
            return view($type.'Dashboard', compact('user'));
        }
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
}