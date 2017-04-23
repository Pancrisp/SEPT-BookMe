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
	*  @group pending
	*  @group employeeAvailable
	*	
	*  Unit test for checking employee availability.
	*
	*  @return void
	*/
	public function employeeAvailabilitySuccessful()
	{
		// Retrieving an existing customer		
		$owner = \App\Business::where('business_id',3)->first();
		// TODO use ::Find employee availability and compare to displayed


		$this->browse(function ($browser) use ($owner) {
		    $browser->visit('/')    
			    ->type('username',$owner->username)
			    ->type('password', 'secret')   
			    ->press('login')
			    ->assertPathIs('/dashboard')   
			    ->assertSee('Hello, '.$owner->customer_name)
			    ->clickLink('Staff availability')
			    ->assertPathIs('/staffAvailability')
			    ->type('name','testEmployee')
			    ->press('search')
			    ->assertSee('testEmployee availability')
 				;
		});
	}
    

	/**
	*  @test 
	*  @group pending
	*  @group employeeAvailable
	*	
	*  Unit test for unauthenticated business owner attempting to check 		*  employee availability.
	*
	*  @return void
	*/
	public function employeeAvailabilityNotAuthenticated()
	{
		// Retrieving an existing customer		
		$owner = \App\Business::where('business_id',3)->first();

		$this->browse(function ($browser) use ($owner) {
		    $browser->visit('/staffAvailability')    
			    ->assertPathIs('/')    
			    ->assertSee('Sign in to access');
		});
	}

	/**
	*  @test 
	*  @group pending
	*  @group employeeAvailable
	*	
	*  Unit test for checking unexisting employee availability.
	*
	*  @return void
	*/
	public function employeeAvailabilityNotFound()
	{
		// Retrieving an existing customer		
		$owner = \App\Business::where('business_id',3)->first();
		// TODO use ::Find employee availability and compare to displayed


		$this->browse(function ($browser) use ($owner) {
		    $browser->visit('/')    
			    ->type('username',$owner->username)
			    ->type('password', 'secret') 
			    ->press('login')
			    ->assertPathIs('/dashboard')   
			    ->assertSee('Hello, '.$owner->customer_name)
			    ->clickLink('Staff availability')
			    ->assertPathIs('/staffAvailability')
			    ->type('name','testEmployee')
			    ->press('search')
			    ->assertSee('testEmployee not found')
 				;
		});
	}

}
