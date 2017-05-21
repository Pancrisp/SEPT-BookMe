<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class SetupBusiness extends BasePage
{
	/**
	* Get the URL for the page.
	*
	* @return string
	*/
	public function url()
	{
		return '/register/business';
	}

	/**
	* Assert that the browser is on the page.
	*
	* @param  Browser  $browser
	* @return void
	*/
	public function assert(Browser $browser)
	{
		$browser->assertPathIs($this->url());
	}

	/**
	* Get the element shortcuts for the page.
	*
	* @return array
	*/
	public function elements()
	{
		return [
		    '@element' => '#selector',
		];
	}

	/**
	* Registers a new Business
	*
	*/
	public function createBusiness(Browser $browser, $business, $password)
	{
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
			    ->type('username',$business->username)
			    ->type('password', $password)   
			    ->press('login')
			    ->assertPathIs('/business/hour/register');
	}


}
