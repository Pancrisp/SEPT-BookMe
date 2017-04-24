<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EmployeeRosterTest extends DuskTestCase
{

	/**
	*  @test 
	*  @group accepted
	*  @group roster
	*	
	*  Unit test for unauthenticated business owner attempting to roster 		*  employees.
	*
	*  @return void
	*/
	public function owner_not_authenticated_roster_employee()
	{
		$business_id = 1;
		// Retrieving a business owner		
		$owner = \App\Business::where('business_id',$business_id)->first();

		$this->browse(function ($browser) use ($owner) {
		    $browser->visit('/newroster')    
			    ->assertPathIs('/')   
			    ->assertSee('Sign in to access');
		});
	}


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
		$business_id = 1;
		$shift = 'Day';
		
		// Retrieving the first business owner		
		$owner = \App\Business::where('business_id',$business_id)->first();
		
		// Retrieving first employee
		$employee = \App\Employee::where('business_id',$business_id)->first();
		// getting availability		
		$availability = explode(' ', $employee->available_days);

		// Calculating next available day from the employee availability
		$toAdd =1;
		$start_day = date('D', strtotime('+'.$toAdd.' day'));
		$next_available = $availability[random_int(0, count($availability)-1)];
		
		while(strcmp($start_day, $next_available) !==0){
			$toAdd++;
			$start_day = date('D', strtotime('+'.$toAdd.' day'));
		}

		$future_date = date('Y-m-d', strtotime('+'.$toAdd.' day'));

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
	*  @group accepted
	*  @group roster
	*	
	*  Unit test for updating the shift in a roster on the same day
	*  
	*  @return void
	*/
	public function owner_roster_employee_existing_update()
	{
		$business_id = 1;
		$shift = 'Day';
		$other_shift = 'Night';
		
		// Retrieving the first business owner		
		$owner = \App\Business::where('business_id',$business_id)->first();
		
		// Retrieving first employee
		$employee = \App\Employee::where('business_id',$business_id)->first();
		// getting availability		
		$availability = explode(' ', $employee->available_days);

		// Calculating next available day from the employee availability
		$toAdd =1;
		$start_day = date('D', strtotime('+'.$toAdd.' day'));
		$next_available = $availability[random_int(0, count($availability)-1)];
		
		while(strcmp($start_day, $next_available) !==0){
			$toAdd++;
			$start_day = date('D', strtotime('+'.$toAdd.' day'));
		}

		$future_date = date('Y-m-d', strtotime('+'.$toAdd.' day'));

		// If there is an employee
		if (isset($employee)){
		$this->browse(function ($browser) use ($owner, $employee, $future_date, $shift, $other_shift) {
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
			    ->radio('shift',$other_shift)
			    ->press('submit')
			    ->assertPathIs('/newroster')
			    ->assertSee('Roster added successfully!')
 				;
		});
		
		// Asserting in database the updated shift
		$criteria = ['date' => $future_date,
				'employee_id' => $employee->employee_id,
				'shift' => $other_shift ];
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
	*  @group bug#5
	*  @group roster
	*	
	*  Unit test for a roster on an invalid day that is not 
	*  part of the employee availability
	*  
	*  @return void
	*/
	public function owner_roster_employee_not_in_availability()
	{
		$business_id = 1;
		$shift = 'Day';
		
		// Retrieving the first business owner		
		$owner = \App\Business::where('business_id',$business_id)->first();
		
		// Retrieving first employee
		$employee = \App\Employee::where('business_id',$business_id)->first();
		// getting availability		
		$availability = explode(' ', $employee->available_days);
		// Exiting in case of availability is all 7 days		
		if (count($availability) ==7 )
			return;
		// Getting not available days
		$not_available = array();		
		for ($i = 1; $i <= 7; $i++) {
			$dayToCheck = date('D', strtotime('+'.$i.' day'));
			if(!in_array($dayToCheck, $availability)){
				array_push($not_available,$dayToCheck);
				
			}
		}

		// Calculating next invalid day from the employee availability
		$toAdd =1;
		$start_day = date('D', strtotime('+'.$toAdd.' day'));
		$next_available = $not_available[random_int(0, count($not_available)-1)];
		print($next_available);
		while(strcmp($start_day, $next_available) !==0){
			$toAdd++;
			$start_day = date('D', strtotime('+'.$toAdd.' day'));
		}

		$future_date = date('Y-m-d', strtotime('+'.$toAdd.' day'));

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
 				;
		});
		
		// Asserting in database, should be null
		$criteria = ['date' => $future_date,
				'employee_id' => $employee->employee_id,
				'shift' => $shift ];
		$should_be_null = \App\Roster::where($criteria)->first();
		$this->assertNull($should_be_null);
		
		// Delete from database in case the invalid roster went ahead
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
