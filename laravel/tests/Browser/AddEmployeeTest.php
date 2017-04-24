<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AddEmployeeTest extends DuskTestCase
{
	
	use DatabaseTransactions;

	/**
	*  @test 
	*  @group accepted
	*  @group addEmployee
	*	
	*  Unit test for unauthenticated business owner attempting to add a 		*  new employee.
	*
	*  @return void
	*/
	public function add_employee_not_authenticated()
	{
		// Retrieving an existing customer		
		$owner = \App\Business::where('business_id',3)->first();

		$this->browse(function ($browser) use ($owner) {
		    $browser->visit('/newstaff')    
			    ->assertPathIs('/')     
			    ->assertSee('Sign in to access');
		});
	}
	/**
	*  @test 
	*  @group accepted
	*  @group addEmployee
	*	
	*  Unit test for a successful employee registration.
	*
	*  @return void
	*/
	public function add_employee_successful()
	{
		$business_id = 1;		
		// Retrieving an existing business owner 		
		$owner = \App\Business::where('business_id',$business_id)->first();
		
		// Generating fake employee data		
		$employee = factory(\App\Employee::class)->make();

		$this->browse(function ($browser) use ($owner, $employee) {
		    $browser->visit('/')    
			    ->type('username',$owner->username)
			    ->type('password', 'secret')  
			    ->press('login')
			    ->assertPathIs('/dashboard')   
			    ->assertSee('Hello, '.$owner->owner_name)
			    ->clickLink('Add new employee')
			    ->assertPathIs('/newstaff')
			    ->type('fullname',$employee->employee_name)
			    ->type('phone',$employee->mobile_phone)
		    	    ->type('taxfileno', $employee->TFN)
		    	    ->select('activity')
			    ->check("input[name='availability[]'][value='Tue']")
			    ->check("input[name='availability[]'][value='Fri']")
			    ->press('submit')
			    ->assertSee('Staff added successfully')
 				;
		});
	}
    

	/**
	*  @test 
	*  @group accepted
	*  @group addEmployee
	*	
	*  Add employee not successfully, existing TFN
	*
	*  @return void
	*/
	public function add_employee_existing_TFN()
	{
		$business_id = 1;		
		// Retrieving an existing business owner 		
		$owner = \App\Business::where('business_id',$business_id)->first();
		
		// Getting first employee 
		$employee = \App\Employee::where('business_id',$business_id)->first();
		// If there is an employee
		if (isset($employee)){
			$this->browse(function ($browser) use ($owner, $employee) {
		    	$browser->visit('/')    
			    ->type('username',$owner->username)
			    ->type('password', 'secret')  
			    ->press('login')
			    ->assertPathIs('/dashboard')   
			    ->assertSee('Hello, '.$owner->owner_name)
			    ->clickLink('Add new employee')
			    ->assertPathIs('/newstaff')
			    ->type('fullname','other_employee_name')
			    ->type('phone',0410000111)
		    	    ->type('taxfileno', $employee->TFN)
		    	    ->select('activity')
			    ->check("input[name='availability[]'][value='Tue']")
			    ->check("input[name='availability[]'][value='Fri']")
			    ->press('submit')
			    ->assertPathIs('/newstaff')
			    ->assertSee('The TFN you have entered is already registered')
 				;
			});
		}else { // If there is not an employee, create one and test it
			$new_employee = factory(\App\Employee::class)->make();

			$this->browse(function ($browser) use ($owner, $new_employee) {
			    $browser->visit('/')    
				    ->type('username',$owner->username)
				    ->type('password', 'secret')  
				    ->press('login')
				    ->assertPathIs('/dashboard')   
				    ->assertSee('Hello, '.$owner->owner_name)
				    ->clickLink('Add new employee')
				    ->assertPathIs('/newstaff')
				    ->type('fullname',$new_employee->employee_name)
				    ->type('phone',$new_employee->mobile_phone)
			    	    ->type('taxfileno', $new_employee->TFN)
			    	    ->select('activity')
				    ->check("input[name='availability[]'][value='Fri']")
				    ->press('submit')
				    ->assertSee('Staff added successfully')
				    // Attempting existing TFN
				    ->type('fullname','other_employee_name')
				    ->type('phone',0410000111)
			    	    ->type('taxfileno', $new_employee->TFN)
			    	    ->select('activity')
				    ->check("input[name='availability[]'][value='Tue']")
				    ->press('submit')
				    ->assertPathIs('/newstaff')
				    ->assertSee('The TFN you have entered is already registered')
					;			
			    });

		}
	}
     
	/**
	*  @test 
	*  @group accepted
	*  @group addEmployee
	*	
	*  Unit test for mandatory fields in the add employee registration form.
	*
	*  @return void
	*/
	public function add_employee_mandatory_fields()
	{
		$business_id = 1;		
		// Retrieving an existing business owner 		
		$owner = \App\Business::where('business_id',$business_id)->first();
		
		// Generating fake employee data		
		$employee = factory(\App\Employee::class)->make();

		$this->browse(function ($browser) use ($owner, $employee) {
		    $browser->visit('/')    
			    ->type('username',$owner->username)
			    ->type('password', 'secret')  
			    ->press('login')
			    ->assertPathIs('/dashboard')   
			    ->assertSee('Hello, '.$owner->owner_name)
			    ->clickLink('Add new employee')
			    ->assertPathIs('/newstaff')
			    // passing blank mandatory fields
			    ->press('submit')
			    ->assertDontSee('Staff added successfully')
			    ->type('fullname',$employee->employee_name)
			    ->press('submit')
			    ->assertDontSee('Staff added successfully')
			    ->type('phone',$employee->mobile_phone)
			    ->press('submit')
			    ->assertDontSee('Staff added successfully')
		    	    ->type('taxfileno', $employee->TFN)
			    ->press('submit')
			    ->assertDontSee('Staff added successfully')
		    	    ->select('activity')
			    ->press('submit')
			    ->assertDontSee('Staff added successfully')
			    ->assertSee('The availability field is required')
 				;
		});
	}
       
}
