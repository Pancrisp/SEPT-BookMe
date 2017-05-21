<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CustomerSummaryBookingsTest extends DuskTestCase
{

	use DatabaseTransactions;

    /**
	*  @test 
	*  @group accepted 
	*  @group customerBookingSummary
	*	
	*  Test for checking whether not authenticated customers
	*  are allowed to visit avalilable booking dashboard
	*
	*  @return void
	*/
    
	public function bookingSummaryCustomerNotAuthenticated()
	{	
		// Retrieving an existing customer		
		$customer = \App\Customer::where('customer_id',2)->first();
		
	
		$this->browse(function ($browser) use ($customer) {
		    $browser->visit('/')
			    ->assertPathIs('/login')   
			    ->assertSee('Sign in to access');
		});
	} 
    

	/**
	*  @test 
	*  @group accepted
	*  @group customerBookingSummary
	*	
	*  Test for checking customer upcoming booking summary
	*
	*  @return void
	*/
	public function bookingUpcomingCustomerCount()
	{
		// Retrieving an existing customer		
		$customer = \App\Customer::first();

		$tomorrow = date('Y-m-d', strtotime('+1 day'));
	
		// Retrieving bookings count of a specific customer		
		$bookingCount = \App\Booking::where('customer_id',$customer->customer_id)
						->where('date', '>=', $tomorrow)->count();

		$this->browse(function ($browser) use ($customer, $bookingCount) {
			$browser->visit('/')   
			    ->type('username',$customer->username)
			    ->type('password', 'secret')  
			    ->press('login')
			    ->assertPathIs('/')
			    ->clickLink('Your booking history')
			    ->assertPathIs('/booking/summary/today')
			    ->clickLink('Upcoming Bookings')
			    ->assertPathIs('/booking/summary/upcoming');
			    if ($bookingCount == 0){
				$browser->assertSee('No booking.');
			    }else if ($bookingCount > 0){
				$browser->assertVisible('table');
			    }
			});
	}


}
