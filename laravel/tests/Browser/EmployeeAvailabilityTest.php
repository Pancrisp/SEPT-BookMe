<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmployeeAvailabilityTest extends DuskTestCase
{
   use DatabaseTransactions;

	/**
	*  @test 
	*  @group accepted
	*  @group employeeAvailable
	*	
	*  Test for unauthenticated business owner attempting to check 		
	*  employee availability.
	*
	*  @return void
	*/
	public function employeeAvailabilityNotAuthenticated()
	{

		$this->browse(function ($browser) {
		    $browser->visit('/staff/availability')    
			    ->assertPathIs('/login')    
			    ->assertSee('Sign in to access');
		});
	}

	/**
	*  @test 
	*  @group accepted
	*  @group employeeAvailable
	*	
	*  Test for checking employee availability.
	*
	*  @return void
	*/
	public function employeeAvailabilitySuccessful()
	{
		// Retrieving an existing business owner		
		$owner = \App\Business::first();

		$tomorrow = date('Y-m-d', strtotime('+1 day'));
	
		// Find employees ids for first business	
		$employees = \App\Employee::where('business_id',$owner->business_id)->pluck('employee_id');

		// Find employee availability and compare to displayed	
		$available = \App\Roster::whereIn('employee_id', $employees)
						->where('date', '=', $tomorrow)->count();

		$this->browse(function ($browser) use ($owner, $available, $employees, $tomorrow) {
		    $browser->visit('/')    
			    ->type('username',$owner->username)
			    ->type('password', 'secret')   
			    ->press('login')
			    ->assertPathIs('/')
			    ->clickLink('Availability')
			    ->assertPathIs('/staff/availability')
			    ->clickLink($tomorrow);
			    if (sizeof($employees) == 0){
				$browser->assertSee('There is no staff.');
			    }else if ($available == 0){
				$browser->assertSee('There is no staff.');
			    }else if ($available > 0){
				$browser->assertVisible('table');
			    }   

		});
	}
    

}
