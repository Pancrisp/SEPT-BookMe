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

        if(!$this->accountExists($data))
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
            return view($type.'Dashboard', $user);
        }
    }

    /**
     * Check if the username entered exists in the database
     *
     * @param  array  $data
     * @return boolean
     */
    private function accountExists(array $data)
    {
        $type = $data['usertype'];

        if($type == 'customer')
        {
            $usernames  = Customer::select('username')->get()->toArray();
            $emails     = Customer::select('email_address')->get()->toArray();
        }
        else
        {
            $usernames  = Business::select('username')->get()->toArray();
            $emails     = Business::select('email_address')->get()->toArray();
        }

        return ( in_array(['username' => $data['username']], $usernames)
            ||   in_array(['email_address' => $data['username']], $emails) );
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