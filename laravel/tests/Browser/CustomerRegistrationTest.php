<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegistrationTest extends DuskTestCase
{
	

	/**
	*  @test
	*  @group registration
	*  @group accepted
	*	
	*  Unit test for a successful user registration.
	*
	*  @return void
	*/
    
	public function registration_successful()
	{
		$customer = factory(\App\Customer::class)->make([ 
			'password' => bcrypt('copsic')
		]);


		$this->browse(function ($browser) use ($customer) {
		    $browser->visit('/register/customer')  
			    ->type('fullname',$customer->customer_name)  
			    ->type('username',$customer->username)
			    ->type('password','copsic')
			    ->type('password_confirmation','copsic')
			    ->type('email',$customer->email_address)
			    ->type('phone',$customer->mobile_phone)
			    ->type('address',$customer->address)	   
			    ->press('signup')
			    ->assertPathIs('/login')
			    ;
		});
		
		// Building criteria assert in database
		$criteria = ['username' => $customer->username ];
		
		// Asserting existence of newly creted customer in the database
		$this->assertDatabaseHas('customers', $criteria);
		
		// Delete newly creted customer from database
		$toDelete = \App\Customer::where('username', $customer->username)->first();
		\App\Customer::destroy($toDelete->customer_id);		

		
	}    

	/**
	*  @test 
	*  @group accepted
	*  @group registration
	*	
	*  Unit test for checking whether a username is unique.
	*
	*  @return void
	*/
    
	public function registration_unique_username()
	{
		// Retrieving an existing customer		
		$customer = \App\Customer::where('customer_id',1)->first();

		$customer_new = factory(\App\Customer::class)->make([ 
			'password' => bcrypt('copsicus123')
		]);

		$this->browse(function ($browser) use ($customer, $customer_new) {
		    $browser->visit('/register/customer')  
			    ->type('fullname',$customer_new->customer_name)  
			    ->type('username',$customer->username)
			    ->type('password','secret')
			    ->type('password_confirmation','secret')
			    ->type('email',$customer_new->email_address)
			    ->type('phone',$customer_new->mobile_phone)
			   ->type('address',$customer_new->address)	   
			    ->press('signup')
			    ->assertPathIs('/register/customer')   
			    ->assertSee('The username has already been taken');
		});
	}  

	/**
	*  @test 
	*  @group accepted
	*  @group registration
	*	
	*  Unit test for checking missing registration details.
	*
	*  @return void
	*/
    
	public function registration_missing_details()
	{
		
		$this->browse(function ($browser) {
		    $browser->visit('/register/customer')
			    ->press('signup')
			    ->assertPathIs('/register/customer')  
			    ->assertSee('The fullname field is required')
			    ->assertSee('The password field is required')
			    ->assertSee('The username field is required')
			    ->assertSee('The email field is required') 
			    ->assertSee('The phone field is required')
			    ->assertSee('The address field is required');
		});
	} 
	
	/**
	*  @test 
	*  @group accepted
	*  @group registration
	*	
	*  Unit test for checking whether password length is met.
	*
	*  @return void
	*/
    
	public function registration_password_length()
	{
		$customer = factory(\App\Customer::class)->make([ 
			'password' => bcrypt('copsicus123')
		]);
	
		$this->browse(function ($browser) use ($customer) {
		    $browser->visit('/register/customer')  
			    ->type('fullname',$customer->customer_name)  
			    ->type('username',$customer->username)
			    ->type('password','1234')
			    ->type('password_confirmation','1234')
			    ->press('signup')
			    ->assertPathIs('/register/customer')   
			   ->assertSee('The password must be at least 6 characters');
		});
	}

	/**
	*  @test 
	*  @group accepted
	*  @group registration
	*	
	*  Unit test for checking whether password confirmation is met.
	*
	*  @return void
	*/
    
	public function registration_password_confirmation()
	{
		$customer = factory(\App\Customer::class)->make([ 
			'password' => bcrypt('copsicus123')
		]);
	
		$this->browse(function ($browser) use ($customer) {
		    $browser->visit('/register/customer')  
			    ->type('fullname',$customer->customer_name)  
			    ->type('username',$customer->username)
			    ->type('password','1234567')
			    ->type('password_confirmation','123456')
			    ->press('signup')
			    ->assertPathIs('/register/customer')   
			   ->assertSee('The password confirmation does not match');
		});
	}
 
	/**
	*  @test 
	*  @group accepted
	*  @group registration
	*	
	*  Unit test for checking whether phone length is met.
	*
	*  @return void
	*/
    
	public function registration_phone_length()
	{
		$customer = factory(\App\Customer::class)->make([ 
			'password' => bcrypt('copsicus123')
		]);
	
		$this->browse(function ($browser) use ($customer) {
		    $browser->visit('/register/customer')  
			    ->type('fullname',$customer->customer_name)  
			    ->type('username',$customer->username)
			    ->type('password','123456')
			    ->type('password_confirmation','123456')
			    ->type('phone',123456789)
			    ->press('signup')
			    ->assertPathIs('/register/customer')   
			   ->assertSee('The phone must be 10 digits');
		});
	}



}
