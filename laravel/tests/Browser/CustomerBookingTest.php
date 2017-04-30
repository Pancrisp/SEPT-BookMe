<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CustomerBookingTest extends DuskTestCase
{
	/**
	*  @test 
	*  @group accepted
	*  @group customerBooking
	*	
	*  Unit test for checking whether not authenticated customers
	*  are allowed to visit booking dashboard
	*
	*  @return void
	*/
    
	public function bookingCustomerNotAuthenticated()
	{	
		// Retrieving an existing customer		
		$customer = \App\Customer::where('customer_id',2)->first();
		
	
		$this->browse(function ($browser) use ($customer) {
		    $browser->visit('/book')
			    ->assertPathIs('/login')   
			    ->assertSee('Sign in to access');
		});
	} 
    
	/**
	*  @test 
	*  @group current
	*  @group customerBooking
	*	
	*  Unit test for checking whether authenticated customers
	*  are allowed to visit booking dashboard
	*
	*  @return void
	*/
    
	public function bookingCustomerAuthenticated()
	{
		$tomorrow = date('Y-m-d', strtotime('+1 day'));
		$booking_time = "09:00";

		// Retieving existing business
		$business = \App\Business::first();
				
		// Retrieving existing customer
		$customer = \App\Customer::first();

		// Retrieving existing service/activity
		$service = \App\Activity::
				where('business_id',$business->business_id)->first();

		// Retrieving existing employee
		$employee = \App\Employee::
				where('business_id',$business->business_id)
				->where('activity_id',$service->activity_id)
				->first();
	
		$this->browse(function ($browser) 
			use ($customer, $business, $tomorrow, $service, 
				$employee, $booking_time) {
		    $browser->visit('/login')    
			    ->type('username',$customer->username)
			    ->type('password', 'secret')
			    ->press('login')
			    ->assertPathIs('/')
			    ->select('business',$business->business_id)
			    ->type("input[id='roster-date']",$tomorrow)
			    ->click("button[type='submit']")
			    ->assertPathIs('/book')
			    ->select('service',$service->activity_id)
			    ->pause(2000)
			    ->type("time",$booking_time)
			    ->pause(1000)
			    ->select('employee',$employee->employee_id)
			    
			    ->pause(2000)
 			;
		});
		
	} 

	/**
	*  @test 
	*  @group pending
	*  @group customerBooking
	*
	* Session is considered as an authenticated user
	* Test for a successful customer booking.
	* @return void
	* TODO Revise table and time selection. 
	*/
    public function successfulBooking()
    {	
	$customer_id = 1;
        // Retrieving an existing customer		
	$customer = \App\Customer::where('customer_id',$customer_id)->first();
		

        $this->browse(function ($browser) use ($customer) {
		$browser->visit('/')   
		    ->type('username',$customer->username)
		    ->type('password', 'secret')  
		    ->press('login')
		    ->type(date("Y-m-d"),'date')
		    ->press('search')
		    ->press('Start Time')
		    ->press('End Time')
		    ->press('Book')
		    ->assertSee('Table booked successfully')
		    ->assertPathIs('/dashboard');
		});
    }
    

    	/**
	*  @test 
	*  @group pending
	*  @group customerBooking
	*
	* Test for an invalid customer attempt to make 
	* a booking.
	* @return void
	*/
    public function invalidBooking()
    {
	
	$customer_id = 1;
        // Retrieving an existing customer		
	$customer = \App\Customer::where('customer_id',$customer_id)->first();
		
	/*First attempt a successful booking*/
        $this->browse(function ($browser) use ($customer) {
		$browser->visit('/')   
		    ->type('username',$customer->username)
		    ->type('password', 'secret')
		    ->press('login')
	    	    ->type(date("Y-m-d"),'date')
		    ->press('search')
		    ->press('Start Time')
		    ->press('End Time')
		    ->press('Book')
		    ->assertSee('Table booked successfully')
		    ->assertPathIs('/dashboard');
		});

	/*Attempting to book the same table at the same time*/

        $this->browse(function ($browser) use ($customer) {
		$browser->visit('/')   
		    ->type('username',$customer->username)
		    ->type('password', 'secret')
		    ->press('login')
	    	    ->type(date("Y-m-d"),'date')
		    ->press('search')
		    ->press('Start Time')
		    ->press('End Time')
		    ->press('Book')
		    ->assertSee('Table unavailable')
		    ->assertPathIs('/dashboard');
		});
    }
}
