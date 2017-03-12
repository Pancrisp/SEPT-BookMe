<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    /**
     * Unit test for a successful user log in.
     *
     * @return void
     */
    public function loginSuccessful()
    {
        $user = factory(App\User::class)->create([
             'email' => 'copsicus@sept.com', 
             'password' => bcrypt('copsicus123')
        ]);

        $this->visit(route('login'))
            ->type('copsicus@sept.com', 'email')
            ->type('copsicus123', 'password')
            ->press('Login')
            ->see('Successfully logged in')
            ->onPage('/dashboard');
    }

    /**
     * Unit test for an  unsuccessful user log in.
     * The password for the account is incorrect.
     * @return void
     */
    public function loginIncorrectPassword()
    {
        $user = factory(App\User::class)->create([
             'email' => 'copsicus@sept.com', 
             'password' => bcrypt('copsicus123')
        ]);

        $this->visit(route('login'))
            ->type('copsicus@sept.com', 'email')
            ->type('copsicus1234', 'password')
            ->press('Login')
            ->seePageIs('/login')
	    ->see('Password is incorrect.');
    }

    /**
     * Unit test for an  unsuccessful user log in.
     * The emai/username account is misspelled 
     * @return void
     */
    public function loginIMisspelledUsername()
    {
        $user = factory(App\User::class)->create([
             'email' => 'copsicus@sept.com', 
             'password' => bcrypt('copsicus123')
        ]);

        $this->visit(route('login'))
            ->type('ccopsicus@sept.com', 'email')
            ->type('copsicus123', 'password')
            ->press('Login')
            ->seePageIs('/login')
	    ->see('Email does not exist.');
    }

    /**
     * Unit test for an  unsuccessful user log in.
     * The emai/username account does not exist
     * @return void
     */
    public function loginUnexistingUsername()
    {
        $user = factory(App\User::class)->make([
             'email' => str_random(10), 
             'password' => bcrypt('copsicus123')
        ]);

        $this->visit(route('login'))
            ->type($user->email, 'email')
            ->type($user->password, 'password')
            ->press('Login')
            ->seePageIs('/login')
	    ->see('Email does not exist.');
    }

    /**
     * Unit test for a user that forgets its password.
     * The password for the account is incorrect.
     * @return void
     */
    public function loginForgottenPassword()
    {
        $user = factory(App\User::class)->create([
             'email' => 'copsicus@sept.com', 
             'password' => bcrypt('copsicus123')
        ]);

        $this->visit(route('login'))
            ->click('Forgot password')
            ->seePageIs('/password_reset')
	    ->type('copsicus@sept.com', 'email')
            ->press('Reset password')
	    ->seePageIs('/login')
	    ->see('Check your email to reset your password.');
    }


}
