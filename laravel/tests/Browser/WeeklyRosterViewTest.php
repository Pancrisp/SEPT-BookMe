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

		$this->browse(function ($browser) {
		    $browser->visit('/roster/summary')    
			    ->assertPathIs('/login')   
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
		    $browser->visit('/login')    
			    ->type('username',$owner->username)
			    ->type('password', 'secret')
			    ->press('login')
			    ->assertPathIs('/')   
			    ->assertSee('Hello, '.$owner->customer_name)
			    ->clickLink('Show roster')
			    ->assertPathIs('/roster/summary')
			    
 				;
		});
	}
    



	/**
	*  @test 
	*  @group accepted
	*  @group viewRoster
	*	
	*  Unit test that checks the first employee shift count for a given business
 	*  and asserts the presence of table with the name of such employee if valid. 
	*
	*  @return void
	*/
	public function business_owner_view_roster_count()
	{
		$business_id = 1;
		$tomorrow = date('Y-m-d', strtotime('+1 day')); 
		$weekAfter = date('Y-m-d', strtotime('+5 day'));	
		// Retrieving an existing business id		
		$owner = \App\Business::where('business_id',$business_id)->first();

		// Find employees ids for first business	
		$employees = \App\Employee::where('business_id',$owner->business_id)->pluck('employee_id');
		
		// Retrieving shifts count from the first employee for the next week
		$rosteredEmployeesCount = \App\Roster::whereIn('employee_id', $employees)
				->whereBetween('date',array($tomorrow, $weekAfter))->pluck('employee_id');


		$this->browse(function ($browser) use ($owner,$rosteredEmployeesCount,$employees) {
		    $browser->visit('/login')    
			    ->type('username',$owner->username)
			    ->type('password', 'secret')   
			    ->press('login')
			    ->assertPathIs('/')   
			    ->assertSee('Hello, '.$owner->customer_name)
			    ->clickLink('Show roster')
			    ->assertPathIs('/roster/summary');
			if (sizeof($rosteredEmployeesCount) > 0){
				$browser->assertVisible('table');
				$browser->with('table', function ($table) use($rosteredEmployeesCount) {
					foreach($rosteredEmployeesCount as $emp){
					$emp_name = \App\Employee::where('employee_id',$emp)
							->pluck('employee_name')->first();
    					$table->assertSee($emp_name);
					}
				});
			}
		});
	}
    




}
