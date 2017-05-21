<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BusinessRegistrationTest extends DuskTestCase
{
	

	/**
	*  @test
	*  @group registration
	*  @group accepted
	*	
	*  Unit test for a successful business registration.
	*
	*  @return void
	*/
    
	public function registration_successful()
	{
		// Creating business model with fake data
		$password = 'copsic';		
		$business = factory(\App\Business::class)->make([ 
			'password' => bcrypt($password)
		]);


		$this->browse(function ($browser) use ($business, $password) {
		    $browser->visit('/register/business')  
			    ->type('business_name',$business->business_name)
			    ->type('owner_name',$business->owner_name)  
			    ->type('username',$business->username)
			    ->type('password',$password)
			    ->type('password_confirmation',$password)
			    ->type('email',$business->email_address)
			    ->type('phone',$business->mobile_phone)
			    ->type('address',$business->address)	   
			    ->press('signup')
			    ->assertPathIs('/login')
			    ;
		});
		
		// Building criteria assert in database
		$criteria = ['username' => $business->username ];
		
		// Asserting existence of newly creted business in the database
		$this->assertDatabaseHas('businesses', $criteria);
		
		// Delete newly creted business from database
		$toDelete = \App\business::where('username', $business->username)->first();
		\App\business::destroy($toDelete->business_id);		

		
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
		// Retrieving an existing business		
		$business = \App\Business::where('business_id',1)->first();
		
		// Creating new business fake data
		$password = 'secret';
		$business_new = factory(\App\Business::class)->make([ 
			'password' => bcrypt($password)
		]);
		

		$this->browse(function ($browser) use ($business, $business_new, $password) {
		    $browser->visit('/register/business') 
			    ->type('business_name',$business_new->business_name)
			    ->type('owner_name',$business_new->owner_name)  
			    ->type('username',$business->username)
			    ->type('password',$password)
			    ->type('password_confirmation',$password)
			    ->type('email',$business_new->email_address)
			    ->type('phone',$business_new->mobile_phone)
			    ->type('address',$business_new->address)	   
			    ->press('signup')
			    ->assertPathIs('/register/business')   
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
		    $browser->visit('/register/business')
			    ->press('signup')
			    ->assertPathIs('/register/business')  
			    ->assertSee('The business name field is required')
			    ->assertSee('The owner name field is required')
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
		// Creating business model with fake data
		$password = '1234';		
		$business = factory(\App\Business::class)->make([ 
			'password' => bcrypt($password)
		]);

		$this->browse(function ($browser) use ($business, $password) {
		    $browser->visit('/register/business')  
			    ->type('business_name',$business->business_name)
			    ->type('owner_name',$business->owner_name)  
			    ->type('username',$business->username)
			    ->type('password',$password)
			    ->type('password_confirmation',$password)
			    ->type('email',$business->email_address)
			    ->type('phone',$business->mobile_phone)
			    ->type('address',$business->address)
			    ->press('signup')	   
			    ->assertPathIs('/register/business')   
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
		// Creating business model with fake data
		$password = 'secret';
		$diff_password = 'other_pass';		
		$business = factory(\App\Business::class)->make([ 
			'password' => bcrypt($password)
		]);

		$this->browse(function ($browser) use ($business, $password, $diff_password) {
		    $browser->visit('/register/business')  
			    ->type('business_name',$business->business_name)
			    ->type('owner_name',$business->owner_name)  
			    ->type('username',$business->username)
			    ->type('password',$password)
			    ->type('password_confirmation',$diff_password)
			    ->type('email',$business->email_address)
			    ->type('phone',$business->mobile_phone)
			    ->type('address',$business->address)
			    ->press('signup')   
			    ->assertPathIs('/register/business')   
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
		// Creating business model with fake data
		$password = 'secret';
		$invalid_phone = 12345678;		
		$business = factory(\App\Business::class)->make([ 
			'password' => bcrypt($password),
			'mobile_phone' => $invalid_phone
		]);

		$this->browse(function ($browser) use ($business, $password) {
		    $browser->visit('/register/business')  
			    ->type('business_name',$business->business_name)
			    ->type('owner_name',$business->owner_name)  
			    ->type('username',$business->username)
			    ->type('password',$password)
			    ->type('password_confirmation',$password)
			    ->type('email',$business->email_address)
			    ->type('phone',$business->mobile_phone)
			    ->type('address',$business->address)
			    ->press('signup')	   
			    ->assertPathIs('/register/business')   
			    ->assertSee('The phone must be 10 digits');
		});

	}

}
