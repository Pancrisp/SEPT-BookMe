<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CustomerAvailableBookingsTest extends DuskTestCase
{

	use DatabaseTransactions;

    /**
	*  @test 
	*  @group accepted 
	*  @group customerAvailableBooking
	*	
	*  Test for checking whether not authenticated customers
	*  are allowed to visit avalilable booking dashboard
	*
	*  @return void
	*/
    
	public function bookingAvailCustomerNotAuthenticated()
	{	
		// Retrieving an existing customer		
		$customer = \App\Customer::where('customer_id',2)->first();
		
	
		$this->browse(function ($browser) use ($customer) {
		    $browser->visit('/dashboard')
			    ->assertPathIs('/')   
			    ->assertSee('Sign in to access');
		});
	} 
    
	/**
	*  @test 
	*  @group pending
	*  @group customerAvailableBooking
	*	
	*  Unit test for checking whether authenticated customers
	*  are allowed to visit available booking dashboard
	*
	*  @return void
	*/
    // TODO Define date picking
	public function bookingAvailCustomerAuthenticated()
	{
		// Retrieving an existing customer		
		$customer = \App\Customer::where('customer_id',2)->first();
		
	
		$this->browse(function ($browser) use ($customer) {
		    $browser->visit('/')    
			    ->type('username',$customer->username)
			    ->type('password', 'secret')
			    ->press('login')
			    ->type('1 Apr, 1990','date')
			    ->press('search')
			    ->assertPathIs('/dashboard') ;
		});
	}

	/**
	*  @test 
	*  @group pending
	*  @group customerAvailableBooking
	*	
	*  Test for checking whether valid dates are accepted when 
	*  checking booking availability
	*
	*  @return void
	*/
	public function bookingAvailCustomerInvalidDate()
	{
		// Retrieving an existing customer		
		$customer = \App\Customer::where('customer_id',2)->first();
	

		$this->browse(function ($browser) use ($customer) {
			$browser->visit('/')   
			    ->type('username',$customer->username)
			    ->type('password', 'secret')  
			    ->press('login')
			    ->type(date("Y-m-d"),'date')
			    ->press('search')
			    ->assertSee('Availability on '.date("Y-m-d"))
			    ->assertPathIs('/dashboard');
			});
	}


}
