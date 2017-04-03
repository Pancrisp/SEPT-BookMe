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
	*  @group current
	*  @group bookingSummary
	*	
	*  Unit test for a successful display of booking summaries
	*  compared to the dababase.
	*
	*  @return void
	*/
	public function bookingSummaryCount()
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
    

}
