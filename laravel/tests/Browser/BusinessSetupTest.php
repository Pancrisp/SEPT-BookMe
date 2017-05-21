<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Browser\Pages\SetupBusiness;

class BusinessSetupTest extends DuskTestCase
{
	
	use DatabaseTransactions;

	/**
	*  @test
	*  @group business_setup
	*  @group accepted
	*	
	*  Unit test for a successful business setup.
	*
	*  @return void
	*/
    
	public function setup_successful()
	{
		// Setting up variables
		$opening_time = "11:00";
		$closing_time = "22:00";
		$custom_time = "13:00";
		$no_time = "00:00";
		$days = ['Mon','Sun'];
		$slot = 30; // 30 minutes
		$service = 'Buffet';
		$slots_count = 2;		

		// Creating business model with fake data
		$password = 'secret';
		$business = factory(\App\Business::class)->make([ 
			'ready' => false
		]);

		// Registrating business and testing business setup
		$this->browse(function ($browser) use ($business, $password, $opening_time, $custom_time, 
					$closing_time, $no_time, $days, $slot, $service, $slots_count){
		    
		    $browser->visit(new SetupBusiness)
			    ->createBusiness($business, $password)	
			    // Workaround to set input time 
			    ->script(["document.querySelector(\"div.hrs-open input[name='opening_time_all']\").value = '".$opening_time."'"]);
		    // Every script needs to run in a separate browser variable
		   $browser->script(["document.querySelector(\"div.hrs-close input[name='closing_time_all']\").value = '".$closing_time."'"]);
		    $browser->check("input[name='special_days[]'][value='".$days[0]."']")
			    ->check("input[name='special_days[]'][value='".$days[1]."']");
		    $browser->script(["document.querySelector('#opening-time-".$days[0]."').value = '".$no_time."'"]);
		    $browser->script(["document.querySelector('#closing-time-".$days[0]."').value = '".$no_time."'"]);
		    $browser->script(["document.querySelector('#opening-time-".$days[1]."').value = '".$custom_time."'"]);
		    $browser->script(["document.querySelector('#closing-time-".$days[1]."').value = '".$closing_time."'"]);
		    $browser->press('next')
			    ->type('slot_period', $slot)
			    ->type('num_of_slots', $slots_count)
			    ->type('activity_name', $service)
			    ->press('done')
			    ->assertSee('Hello, '.$business->owner_name)
			    ->clickLink('Logout');
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
	*  @group business_setup
	*	
	*  Test thats checks validations when setting up a business .
	*
	*  @return void
	*/
    
	public function setup_business_validation()
	{
		// Setting up variables
		$opening_time = "11:00";
		$closing_time = "22:00";
		$custom_time = "13:00";
		$no_time = "00:00";
		$days = ['Mon','Sun'];
		$slot = 1440; 
		$service = 'Buffet';
		$slots_count = 2;		

		// Creating business model with fake data
		$password = 'secret';
		$business = factory(\App\Business::class)->make([ 
			'ready' => false
		]);

		// Registrating business and testing business setup
		$this->browse(function ($browser) use ($business, $password, $opening_time, $custom_time, 
					$closing_time, $no_time, $days, $slot, $service, $slots_count){
		    
		    $browser->visit(new SetupBusiness)
			    ->createBusiness($business, $password)	
			    // Workaround to set input times
			    ->script(["document.querySelector(\"div.hrs-open input[name='opening_time_all']\").value = '".$opening_time."'"]);
		    // Every script needs to run in a separate browser variable
		    $browser->script(["document.querySelector(\"div.hrs-close input[name='closing_time_all']\").value = '".$closing_time."'"]);
		    $browser->check("input[name='special_days[]'][value='".$days[0]."']")
			    ->check("input[name='special_days[]'][value='".$days[1]."']");
		    $browser->script(["document.querySelector('#opening-time-".$days[0]."').value = '".$no_time."'"]);
		    $browser->script(["document.querySelector('#closing-time-".$days[0]."').value = '".$no_time."'"]);
		    $browser->script(["document.querySelector('#opening-time-".$days[1]."').value = '".$custom_time."'"]);
		    $browser->script(["document.querySelector('#closing-time-".$days[1]."').value = '".$closing_time."'"]);
		    $browser->press('next')
			    ->type('slot_period', $slot)
			    ->type('num_of_slots', $slots_count)
			    ->type('activity_name', $service)
			    ->press('add-another-one')
			    ->assertSee('Service added successfully!')
			    ->press('done')
			    // Empty num of slots
			    ->assertPathIs('/business/activity/register')
			    ->type('num_of_slots', $slots_count)
			    ->press('done')
			    // Empty activity/service name
			    ->assertPathIs('/business/activity/register')
			    ->type('activity_name', $service.'1')
			    ->press('done')
			    ->assertSee('Hello, '.$business->owner_name)
			    ->assertPathIs('/')
			    ->clickLink('Logout');
			    
		});
		

		// Building criteria assert in database
		$criteria = ['username' => $business->username ];
		
		// Asserting existence of newly creted business in the database
		$this->assertDatabaseHas('businesses', $criteria);
		
		// Delete newly creted business from database
		$toDelete = \App\business::where('username', $business->username)->first();
		\App\business::destroy($toDelete->business_id);		
	} 



}
