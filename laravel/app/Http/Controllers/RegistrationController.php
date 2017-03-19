<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class RegistrationController
{
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if($validator->fails()) {
            return Redirect::back()
                ->withInput()
                ->withErrors($validator);
        }

        if($this->create($request->all())){
            return redirect('/');
        }
        else{
            return Redirect::back()
                ->withInput()
                ->withErrors(array('username' => 'Username already exists'));
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validator(array $data)
    {
        return Validator::make($data, [
            'fullname'  => 'required|max:255',
            'username'  => 'required|max:255',
            'password'  => 'required|min:6|confirmed',
            'email'     => 'required|email|max:255|unique:users',
            'phone'     => 'required|digits:10',
            'address'   => 'required'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return boolean
     */
    private function create(array $data)
    {
        $usernames = Customer::select('username')->get()->toArray();

        if(!in_array(['username' => $data['username']], $usernames))
        {
            Customer::create([
                'customer_name' => $data['fullname'],
                'username'      => $data['username'],
                'password'      => bcrypt($data['password']),
                'email_address' => $data['email'],
                'mobile_phone'  => $data['phone'],
                'address'       => $data['address']
            ]);

            return true;
        }

        else{
            return false;
        }
    }
}