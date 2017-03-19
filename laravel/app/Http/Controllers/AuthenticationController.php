<?php

namespace App\Http\Controllers;


use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AuthenticationController
{
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
            return redirect('dashboard');

    }

    /**
     * Check if the username entered exists in the database
     *
     * @param  array  $data
     * @return boolean
     */
    private function accountExists(array $data)
    {
        $usernames  = Customer::select('username')->get()->toArray();
        $emails     = Customer::select('email_address')->get()->toArray();

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
        $customer = Customer::where('username', $data['username'])
            ->orWhere('email_address', $data['username'])
            ->first();

        return password_verify($data['password'], $customer->password);
    }
}