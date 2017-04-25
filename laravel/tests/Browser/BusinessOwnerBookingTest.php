<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BusinessOwnerBookingTest extends DuskTestCase
{
    
	/**
	*  @test 
	*  @group accepted
	*  @group ownerBooking
	*	
	*  Unit test for unauthenticated business owner attempting to make a  		*  booking.
	*  Note: this test has to be run first, since the browser maintains the 
	*  	session alive while opened. 
	*
	*  @return void
	*/
	public function business_owner_booking_not_authenticated()
	{
		$this->browse(function ($browser) {
		    $browser->visit('/staffbooking')
			    ->assertSee('Sign in to access');
		});
	}

	/**
	*  @test 
	*  @group accepted
	*  @group ownerBooking
	*	
	*  Unit test for a successful booking done by an authenticated 
	*  business owner on behalf of a valid customer.
	*
	*  @return void
	*/
	public function business_owner_booking_successful()
	{
		$business_id = 1;		
		// Retrieving an existing business id		
		$owner = \App\Business::where('business_id',$business_id)->first();

		$this->browse(function ($browser) use ($owner) {
		    $browser->visit('/')    
			    ->type('username',$owner->username)
			    ->type('password', 'secret')
			    ->press('login')
			    ->assertPathIs('/dashboard')   
			    ->assertSee('Hello, '.$owner->customer_name)
			    ->clickLink('Make a new booking for customers')
			    ->assertPathIs('/staffbooking')
			    
 				;
		});
	}
}
