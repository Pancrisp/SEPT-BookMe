<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WeeklyRosterViewTest extends DuskTestCase
{
    
	use DatabaseTransactions;

	/**
	*  @test 
	*  @group accepted
	*  @group viewRoster
	*	
	*  Unit test for unauthenticated business owner attempting to check 		*  weekly rosters.
	*  Note: this test has to be run first, since the browser maintains the 
	*  	session alive while opened. 
	*
	*  @return void
	*/
	public function business_owner_not_authenticated_view_roster()
	{
		$business_id = 1;		
		// Retrieving an existing business id		
		$owner = \App\Business::where('business_id',$business_id)->first();

		$this->browse(function ($browser) use ($owner) {
		    $browser->visit('/viewroster')    
			    ->assertPathIs('/')   
			    ->assertSee('Sign in to access');
		});
	}

	/**
	*  @test 
	*  @group accepted
	*  @group viewRoster
	*	
	*  Unit test for a successful display of weekly rosters
	*  done by an authenticated business owner.
	*
	*  @return void
	*/
	public function business_owner_booking_summary_successful()
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
			    ->clickLink('Show all employees')
			    ->assertPathIs('/viewroster')
			    
 				;
		});
	}
    



	/**
	*  @test 
	*  @group pending
	*  @group viewRosterPending
	*	
	*  Unit test that checks the count of shifts for a given business 		*  and asserts according. 
	*
	*  @return void
	*/
	public function business_owner_weekly_roster_count()
	{
		$business_id = 1;		
		// Retrieving an existing business id		
		$owner = \App\Business::where('business_id',$business_id)->first();
		
		// Retrieving bookings count of a specific business		
		$rosterCount = \App\Roster::where('business_id',$business_id)->count();

		$this->browse(function ($browser) use ($owner,$rosterCount) {
		    $browser->visit('/')    
			    ->type('username',$owner->username)
			    ->type('password', 'secret')   
			    ->press('login')
			    ->assertPathIs('/dashboard')   
			    ->assertSee('Hello, '.$owner->customer_name)
			    ->clickLink('Bookings Overview')
			    ->assertPathIs('/bookings/summary');
			if ($rosterCount == 0){
				$browser->assertSee('Currently no booking.');
			}else if ($rosterCount > 0){
				$browser->assertVisible('table');
			}
		});
	}
    




}
