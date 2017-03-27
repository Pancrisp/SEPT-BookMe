<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laracasts\Integrated\Extensions\Laravel as IntegrationTest;

class LoginTest extends TestCase
{
    /**
     * @test 
     *	Unit test for a successful user log in.
     *
     * @return void
     */
    public function testLoginSuccessful()
    {
        $user = factory(\App\Customer::class)->make([
	     'username' => 'copsicus', 
             'password' => bcrypt('copsicus123')
        ]);

        $this->visit(route('login'))
            ->type('copsiccus', 'username')
            ->type('copsicus123', 'password')
	    ->select('customer', 'usertype')
            ->press('login')
            ->see('Hello, '.$user->name)
            ->onPage('/dashboard');
    }

    /**
     * Unit test for an  unsuccessful user log in.
     * The password for the account is incorrect.
     * @return void
     */
    public function testLoginIncorrectPassword()
    {
        $user = factory(\App\User::class)->create([
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
    public function testLoginMisspelledUsername()
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
    public function testLoginUnexistingUsername()
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
    public function testLoginForgottenPassword()
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
