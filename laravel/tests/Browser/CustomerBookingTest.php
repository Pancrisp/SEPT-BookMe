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
    
	public function customer_booking_Not_Authenticated()
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
	*  @group accepted
	*  @group customerBooking
	*	
	*  Unit test for checking whether authenticated customers
	*  are allowed to make bookings
	*
	*  @return void
	*/
    
	public function customer_successful_booking()
	{
		$tomorrow = date('Y-m-d', strtotime('+1 day'));
		$booking_time = "11:00";

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
		
		// Retrieving if booking exists
		$booking = \App\Booking::where('date',$tomorrow)
			// Booking query needs an extra '00' in the start_time 
					->where('start_time', $booking_time.':00')
					->where('customer_id',$customer->customer_id)
					->where('business_id',$business->business_id)
					->where('employee_id',$employee->employee_id)
					->first();
				
		if ( !isset($booking)){
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
			    ->select('employee',$employee->employee_id)
			    // Workaround to set input time 
			    ->script([
            			"document.querySelector('#time').value = '".$booking_time."'"])
			;
		
		    $browser->pause(500)
			    ->click("button[type='submit']")		
			    ->assertSee('Thank you for making a booking')
 			;
		});
		
		// Asserting existence of booking		
		$criteria = ['date'=>$tomorrow,
				'start_time'=> $booking_time.':00',
				'customer_id'=> $customer->customer_id,
				'business_id'=> $business->business_id,
				'employee_id'=> $employee->employee_id];

		$this->assertDatabaseHas('bookings', $criteria);
		// Delete from database
		$deletedRows = \App\Booking::where($criteria)->delete();
		}
		
	} 
    

    	/**
	*  @test 
	*  @group accepted
	*  @group customerBooking
	*
	* Test for a customer attempting to make a booking at an unavailable  
	* time slot.
	* @return void
	*/
    public function customer_booking_invalid()
    	{
	
		$tomorrow = date('Y-m-d', strtotime('+1 day'));
		$booking_time = "11:00";
		$do_test = false;

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
		
		// Retrieving first existing booking
		$booking = \App\Booking::where('date','>=',$tomorrow)
			// Booking query needs an extra '00' in the start_time 
					->where('customer_id',$customer->customer_id)
					->where('business_id',$business->business_id)
					->where('employee_id',$employee->employee_id)
					->first();

		// if booking does not exist, create it for the test
		if (!isset($booking)){	
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
			    ->select('employee',$employee->employee_id)
			    // Workaround to set input time 
			    ->script([
            			"document.querySelector('#time').value = '".$booking_time."'"])
			;
		
		    $browser->pause(500)
			    ->click("button[type='submit']")		
			    ->assertSee('Thank you for making a booking')

 			;
		});
		
		// Updating content of $booking
		$booking = \App\Booking::where('date','>=',$tomorrow)
			// Booking query needs an extra '00' in the start_time 
					->where('customer_id',$customer->customer_id)
					->where('business_id',$business->business_id)
					->where('employee_id',$employee->employee_id)
					->first();
		 
		$do_test = true;
		}		
		
		if ( isset($booking) || $do_test){
		$this->browse(function ($browser) 
			use ($customer, $business, $tomorrow, $service, 
				$employee, $booking) {
		    $browser->visit('/login')    
			    ->type('username',$customer->username)
			    ->type('password', 'secret')
			    ->press('login')
			    ->assertPathIs('/')
			    ->select('business',$business->business_id)
			    ->type("input[id='roster-date']",$booking->date)
			    ->click("button[type='submit']")
			    ->assertPathIs('/book')
			    ->select('service',$service->activity_id)
			    ->select('employee',$employee->employee_id)
			    // Workaround to set input time 
			    ->script([
            			"document.querySelector('#time').value = '".substr($booking->start_time, 0, -3)."'"])
			;
		
		    $browser->pause(500)
			    ->click("button[type='submit']")		
			    ->assertSee('Slot selected is not valid')
 			;
		});
		
		// Asserting existence of booking		
		$criteria = ['date'=>$tomorrow,
				'start_time'=> $booking->start_time,
				'customer_id'=> $customer->customer_id,
				'business_id'=> $business->business_id,
				'employee_id'=> $employee->employee_id];

		$this->assertDatabaseHas('bookings', $criteria);
		// Delete from database
		$deletedRows = \App\Booking::where($criteria)->delete();
		}
		
    }
}
