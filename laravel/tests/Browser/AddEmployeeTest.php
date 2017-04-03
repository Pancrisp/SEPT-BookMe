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
	*  @group pending
	*  @group addEmployee
	*	
	*  Unit test for a successful employee registration.
	*
	*  @return void
	*/
	public function addEmployeeSuccessful()
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
			    ->clickLink('+ Add new staff')
			    ->assertPathIs('/newstaff')
			    ->type('name','testEmployee')
			    ->type('mobile',1234567890)
		    	    ->type('employeeTFN', '12345678')
		    	    ->type('role','waiter')
			    ->press('submit')
			    ->assertSee('Employee added successfully')
 				;
		});
	}
    

	/**
	*  @test 
	*  @group bug#1
	*  @group addEmployee
	*	
	*  Unit test for unauthenticated business owner attempting to add a 		*  new employee.
	*
	*  @return void
	*/
	public function addEmployeeNotAuthenticated()
	{
		// Retrieving an existing customer		
		$owner = \App\Business::where('business_id',3)->first();

		$this->browse(function ($browser) use ($owner) {
		    $browser->visit('/newstaff')    
			    ->assertPathIs('/')   
			    //->on('/')   
			    ->assertSee('Sign in to access');
		});
	}

	/**
	*  @test 
	*  @group pending
	*  @group addEmployee
	*	
	*  Add employee not successfully, existing TFN
	*
	*  @return void
	*/
	public function addEmployeeExistingTFN()
	{
		// Retrieving an existing customer		
		$owner = \App\Business::where('business_id',3)->first();
		//TODO factory for employee		
		$employee = \App\Business::where('business_id',1)->first();
		

		$this->browse(function ($browser) use ($owner, $employee) {
		    $browser->visit('/')    
			    ->type('username',$owner->username)
			    ->type('password', 'secret')
			    ->radio('usertype', 'business' )    
			    ->press('login')
			    ->assertPathIs('/dashboard')   
			    ->assertSee('Hello, '.$owner->customer_name)
			    ->clickLink('+ Add new staff')
			    ->assertPathIs('/newstaff')
			   /* ->type('name','testEmployee')
			    ->type('mobile',1234567890)
		    	    ->type('employeeTFN', '12345678')
		    	    ->type('role','waiter')
			    ->press('submit')
 			    ->press('add')
            		    ->assertSee('Duplicate Employee TFN')*/
			;
		});
	}
     

       
}
