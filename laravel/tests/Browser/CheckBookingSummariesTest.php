<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CheckBookingSummariesTest extends DuskTestCase
{
    use DatabaseTransactions;

	/**
	*  @test 
	*  @group accepted
	*  @group bookingSummary
	*	
	*  Unit test for a successful display of booking summaries
	*  done by an authenticated business owner.
	*
	*  @return void
	*/
	public function bookingSummarySuccessful()
	{
		// Retrieving an existing customer		
		$owner = \App\Business::where('business_id',3)->first();

		$this->browse(function ($browser) use ($owner) {
		    $browser->visit('/')    
			    ->type('username',$owner->username)
			    ->type('password', 'secret')
			    ->radio('usertype', 'business' )    
			    ->press('login')
			    ->assertPathIs('/dashboard')   
			    ->assertSee('Hello, '.$owner->customer_name)
			    ->clickLink('Bookings Overview')
			    ->assertPathIs('/bookings/summary/'.$owner->business_id)
			    
 				;
		});
	}
    

	/**
	*  @test 
	*  @group bug#3
	*  @group bookingSummary
	*	
	*  Unit test for unauthenticated business owner attempting to check 		*  booking Summaries.
	*
	*  @return void
	*/
	public function bookingSummaryNotAuthenticated()
	{
		// Retrieving an existing customer		
		$owner = \App\Business::where('business_id',3)->first();

		$this->browse(function ($browser) use ($owner) {
		    $browser->visit('/bookings/summary/'.$owner->business_id)    
			    ->assertPathIs('/')   
			    ->assertSee('Sign in to access');
		});
	}

	/**
	*  @test 
	*  @group accepted
	*  @group bookingSummary
	*	
	*  Unit test that checks the count of bookings for a given business 		*  and asserts according. 
	*
	*  @return void
	*/
	public function bookingSummaryCount()
	{
		$business_id = 3; 		
		// Retrieving an existing customer		
		$owner = \App\Business::where('business_id',$business_id)->first();
		// Retrieving bookings count of a specific business		
		$bookingCount = \App\Booking::where('business_id',$business_id)->count();

		$this->browse(function ($browser) use ($owner,$bookingCount) {
		    $browser->visit('/')    
			    ->type('username',$owner->username)
			    ->type('password', 'secret')
			    ->radio('usertype', 'business' )    
			    ->press('login')
			    ->assertPathIs('/dashboard')   
			    ->assertSee('Hello, '.$owner->customer_name)
			    ->clickLink('Bookings Overview')
			    ->assertPathIs('/bookings/summary/'.$owner->business_id);
			if ($bookingCount == 0){
				$browser->assertSee('Currently no booking.');
			}else if ($bookingCount > 0){
				$browser->assertVisible('table');
			}
		});
	}
    

}
