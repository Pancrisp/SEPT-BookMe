<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AuthenticationController
{
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
}