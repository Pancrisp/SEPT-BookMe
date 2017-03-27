<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->browse(function ($browser) {
            $browser->visit('/')
                    ->assertSee('Laravel');
        });
    }

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

        $this->browse(function ($browser) {
            $browser->visit('/')
	        
	    ->type('username','copsiccus')
            ->type('password', 'copsicus123')
	    ->radio('usertype', 'customer' )    
            ->press('login')
            ->see('Hello, '.$user->name)
            ->onPage('/dashboard');
	});
    }
}
