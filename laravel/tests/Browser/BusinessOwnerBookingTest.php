<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BusinessOwnerBookingTest extends DuskTestCase
{
    
	/**
	*  @test 
	*  @group bug#7
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
		    $browser->visit('/booking/make')
			    ->assertPathIs('/login')
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
		
		$tomorrow = date('Y-m-d', strtotime('+2 day'));
		$booking_time = "16:00";

		// Retieving existing business
		$business = \App\Business::first();
				
		// Retrieving existing customer
		$customer = \App\Customer::first();

		// Retrieving existing employee
		$employee = \App\Employee::
				where('business_id',$business->business_id)
				->first();
	
		// Retrieving existing service/activity
		$service = $employee->activity_id;
		
		// Retrieving roster
		$roster = \App\Roster::
				where('date','>',$tomorrow)
				->where('employee_id',$employee->employee_id)
				->first();
		
		// Retrieving if booking exists
		$booking = \App\Booking::where('date',$roster->date)
			// Booking query needs an extra '00' in the start_time 
					->where('start_time', $booking_time.':00')
					->where('customer_id',$customer->customer_id)
					->where('business_id',$business->business_id)
					->where('employee_id',$employee->employee_id)
					->first();

		if ( !isset($booking)){
		$this->browse(function ($browser) 
			use ($customer, $business, $tomorrow, $service, 
				$employee, $booking_time, $roster) {
		    $browser->visit('/login')    
			    ->type('username',$business->username)
			    ->type('password', 'secret')
			    ->press('login')
			    ->assertPathIs('/')
			    ->assertSee($business->business_name)
			    ->clickLink('New booking')
			    ->assertPathIs('/booking/make')	
			    ->type("input[id='roster-date']",$roster->date)
			    ->click("input[name='username']")
			    ->type('username', $customer->username)
			    ->click("button[type='submit']")
			    ->assertPathIs('/book')
			    ->select('service',$service)
			    
			    // Workaround to set input time 
			    ->script(["document.querySelector('#time').value = '".$booking_time."'"]);
		
		    $browser->pause(500)
			    ->select('employee',$employee->employee_id)
			    ->click("button[type='submit']")		
			    ->assertSee('made successfully')
 			;
		});
		
		// Asserting existence of booking		
		$criteria = ['date'=>$roster->date,
				'start_time'=> $booking_time.':00',
				'customer_id'=> $customer->customer_id,
				'business_id'=> $business->business_id,
				'employee_id'=> $employee->employee_id];

		$this->assertDatabaseHas('bookings', $criteria);
		// Delete from database
		$deletedRows = \App\Booking::where($criteria)->delete();
		}

	}
}
