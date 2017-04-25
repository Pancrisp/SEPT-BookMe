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
	public function business_owner_view_roster_not_authenticated()
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
	public function business_owner_view_roster_successful()
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
	*  @group accepted
	*  @group viewRoster
	*	
	*  Unit test that checks the first employee shift count for a given business 		*  and asserts the presence of table with the name of such employee if valid. 
	*
	*  @return void
	*/
	public function business_owner_view_roster_count()
	{
		$business_id = 1;
		$tomorrow = date('Y-m-d', strtotime('+1 day')); 
		$weekAfter = date('Y-m-d', strtotime('+7 day'));	
		// Retrieving an existing business id		
		$owner = \App\Business::where('business_id',$business_id)->first();
		$employee = \App\Employee::where('business_id', $business_id)->first();
		
		// Retrieving shifts count from the first employee for the next week
		$rosterCount = \App\Roster::where('employee_id',$employee->employee_id)->whereBetween('date',array($tomorrow, $weekAfter))->count();

		$this->browse(function ($browser) use ($owner,$rosterCount,$employee) {
		    $browser->visit('/')    
			    ->type('username',$owner->username)
			    ->type('password', 'secret')   
			    ->press('login')
			    ->assertPathIs('/dashboard')   
			    ->assertSee('Hello, '.$owner->customer_name)
			    ->clickLink('Show all employees')
			    ->assertPathIs('/viewroster');
			if ($rosterCount > 0){
				$browser->assertVisible('table');
				$browser->with('table', function ($table) use($employee) {
    				$table->assertSee($employee->employee_name);
				});
			}
		});
	}
    




}
