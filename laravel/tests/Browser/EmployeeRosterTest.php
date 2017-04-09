<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EmployeeRosterTest extends DuskTestCase
{
	// TODO Test that roster follows on availability
	
	/**
	*  @test 
	*  @group accepted
	*  @group roster
	*	
	*  Unit test for a successful employee roster
	*  
	*  @return void
	*/
	public function owner_roster_employee_successful()
	{
		$business_id = 3;
		$shift = 'Day';
		// Retrieving an existing customer		
		$owner = \App\Business::where('business_id',$business_id)->first();
		$employee = \App\Employee::where('business_id',$business_id)->first();
		$future_date = date('Y-m-d', strtotime(' +1 day'));
		// If there is an employee
		if (isset($employee)){
		$this->browse(function ($browser) use ($owner, $employee, $future_date, $shift) {
		    $browser->visit('/')    
			    ->type('username',$owner->username)
			    ->type('password', 'secret')
			    ->press('login')
			    ->assertPathIs('/dashboard')   
			    ->assertSee('Hello, '.$owner->customer_name)
			    ->clickLink('Add employee working time')
			    ->assertPathIs('/newroster')
			    ->select('employee_id',$employee->employee_id."")
			    ->type("input[id='roster-date']",$future_date)
			    ->radio('shift',$shift)
			    //->press('submit')
			    ->click("button[name='submit'")
			    ->assertSee('Roster added successfully!')

 				;
		});
		
		// Asserting in database
		$criteria = ['date' => $future_date,
				'employee_id' => $employee->employee_id,
				'shift' => $shift ];
		$this->assertDatabaseHas('rosters', $criteria);
		// Delete from database
		$deletedRows = \App\Roster::where($criteria)->delete();
		
		}else{
		$this->browse(function ($browser) use ($owner) {
		    $browser->visit('/')    
			    ->type('username',$owner->username)
			    ->type('password', 'secret')
			    ->press('login')
			    ->assertPathIs('/dashboard')   
			    ->assertSee('Hello, '.$owner->customer_name)
			    ->clickLink('Add employee working time')
			    ->assertPathIs('/newroster');
		});
		}
		

		
	}
    

	/**
	*  @test 
	*  @group bug#2
	*  @group roster
	*	
	*  Unit test for unauthenticated business owner attempting to roster 		*  employees.
	*
	*  @return void
	*/
	public function owner_not_authenticated_roster_employee()
	{
		// Retrieving an existing customer		
		$owner = \App\Business::where('business_id',3)->first();

		$this->browse(function ($browser) use ($owner) {
		    $browser->visit('/newroster')    
			    ->assertPathIs('/')   
			    ->assertSee('Sign in to access');
		});
	}

  	/**
	*  @test 
	*  @group bug#3
	*  @group roster
	*	
	*  Unit test for adding an already existing roster
	*  
	*  @return void
	*/
	public function owner_roster_employee_existing_error()
	{
		$business_id = 3;
		$shift = 'Day';
		// Retrieving an existing customer		
		$owner = \App\Business::where('business_id',$business_id)->first();
		$employee = \App\Employee::where('business_id',$business_id)->first();
		$future_date = date('Y-m-d', strtotime(' +1 day'));
		// If there is an employee
		if (isset($employee)){
		$this->browse(function ($browser) use ($owner, $employee, $future_date, $shift) {
		    $browser->visit('/')    
			    ->type('username',$owner->username)
			    ->type('password', 'secret')
			    ->press('login')
			    ->assertPathIs('/dashboard')   
			    ->assertSee('Hello, '.$owner->customer_name)
			    ->clickLink('Add employee working time')
			    ->assertPathIs('/newroster')
			    ->select('employee_id',$employee->employee_id."")
			    ->type("input[id='roster-date']",$future_date)
			    ->radio('shift',$shift)
			    ->press('submit')
			    ->assertSee('Roster added successfully!')
			    // Attempting to submit the same information
			    ->select('employee_id',$employee->employee_id."")
			    ->type("input[id='roster-date']",$future_date)
			    ->radio('shift',$shift)
			    ->press('submit')
			    ->assertSee('Roster already exists')
 				;
		});
		
		// Asserting in database
		$criteria = ['date' => $future_date,
				'employee_id' => $employee->employee_id,
				'shift' => $shift ];
		$this->assertDatabaseHas('rosters', $criteria);
		// Delete from database
		$deletedRows = \App\Roster::where($criteria)->delete();
		
		}else{
		$this->browse(function ($browser) use ($owner) {
		    $browser->visit('/')    
			    ->type('username',$owner->username)
			    ->type('password', 'secret')
			    ->press('login')
			    ->assertPathIs('/dashboard')   
			    ->assertSee('Hello, '.$owner->customer_name)
			    ->clickLink('Add employee working time')
			    ->assertPathIs('/newroster');
		});
		}
		
	}
}
