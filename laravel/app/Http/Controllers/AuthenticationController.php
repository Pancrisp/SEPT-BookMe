<?php

namespace App\Http\Controllers;


use App\Business;
use App\Customer;
use App\User;
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
            // if successfully authenticated
            if(Auth::attempt(['email' => $email, 'password' => $data['password']]))
            {
                // get the count
                $result = $this->passwordCount($email, 'get');

                // when count is less than 5, reset count and redirect to dashboard
                if($result['count'] < 5)
                {
                    $this->passwordCount($email, 'reset');
                    return Redirect::to('/');
                }
                // when count is more than 5, redirect back to ask to contact admin
                else
                    return Redirect::back()
                        ->withInput()
                        ->withErrors(array('password' => $result['message']));
            }

            // when authentication fails
            else
            {
                // increment the count
                $result = $this->passwordCount($email, 'increment');

                // redirect back with input and error message
                return Redirect::back()
                    ->withInput()
                    ->withErrors(array('password' => $result['message']));
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

    /**
     * carry out specified action when user trying to login
     *
     * reset wrong_password_count when successfully login
     * increment the count when authentication fails
     *
     * return the count and error message accordingly
     *
     * @param $email
     * @param $action
     * @return array
     */
    private function passwordCount($email, $action)
    {
        // get the user according to email
        $user = User::where('email', $email)->first();

        // change the count according to action input
        if($action == 'reset')
            $user->wrong_password_count = 0;
        elseif($action == 'increment')
            $user->wrong_password_count++;

        // save to DB
        $user->save();

        // prepare result, consisting count and error message
        $result = [];
        $result['count'] = $user->wrong_password_count;

        // customise error message according to count
        if($result['count'] < 5)
            $result['message'] = 'Incorrect password. Please try again!';
        else
            $result['message'] = 'This account has been locked. Please contact admin to reset password.';

        return $result;
    }
}