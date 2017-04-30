<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends DuskTestCase
{
	use DatabaseTransactions;	
	protected $customerr;
	
	public function setUp()
	{
		parent::setUp();

	}
    /**
     *  @test 
     *  @group accepted
     *  @group login
     *	Unit test for a successful costumer log in.
     *
     *  @return void
     */
    public function login_customer_successful()
    {
        // Retrieving an existing customer
	$customer = \App\Customer::where('customer_id',1)->first();
	
        $this->browse(function ($browser) use ($customer) {
            $browser->visit('/login')    
		    ->type('username',$customer->username)
		    ->type('password', 'secret')   
		    ->press('login')
		    ->assertPathIs('/')   
		    ->assertSee('Hello, '.$customer->customer_name);
	});
    }
    /**
     *  @test 
     *  @group accepted
     *  @group login
     *	Unit test for a successful business owner log in.
     *
     *  @return void
     */
    public function login_business_owner_successful()
    {
        // Retrieving an existing customer
	$owner = \App\Business::where('business_id',1)->first();
	
        $this->browse(function ($browser) use ($owner) {
            $browser->visit('/login')    
		    ->type('username',$owner->username)
		    ->type('password', 'secret')   
		    ->press('login')
		    ->assertPathIs('/')   
		    ->assertSee('Hello, '.$owner->owner_name.' from '.$owner->business_name);
	});
    }
   /**
     * @test 
     * @group accepted
     * @group login
     * Unit test for an  unsuccessful user log in.
     * The password for the account is incorrect.
     * @return void
     */
    public function login_incorrect_password()
    {
        
	// Retrieving an existing customer		
	$customer = \App\Customer::where('customer_id',2)->first();

        $this->browse(function ($browser) use ($customer) {
            $browser->visit('/login')   
		    ->type('username',$customer->username)
		    ->type('password', 'other_password')   
		    ->press('login')
		    ->assertPathIs('/login')
		    ->assertSee('Incorrect password. Please try again!');
	});

    }

    /**
     * @test 
     * @group accepted
     * @group login
     * Unit test for an  unsuccessful user log in.
     * The emai/username account is misspelled 
     * @return void
     */
    public function login_misspelled_username()
    {
        $customer = factory(\App\Customer::class)->make([ 
             		'username' => 'testCustomerr'
       	]);

	$this->browse(function ($browser) use ($customer) {
            $browser->visit('/login')   
		    ->type('username',$customer->username)
		    ->type('password', 'copsicus123')  
		    ->press('login')
		    ->assertPathIs('/login')
		    ->assertSee('Account not found.');
	});
    }

    /**
     * @test 
     * @group accepted
     * @group login
     * Unit test for an  unsuccessful user log in.
     * The emai/username account does not exist
     * @return void
     */
    public function login_not_existing_username()
    {
	
        $this->browse(function ($browser) {
            $browser->visit('/login')   
		    ->type('username','unexisting_customer')
		    ->type('password', 'copsicus123') 
		    ->press('login')
		    ->assertPathIs('/login')
		    ->assertSee('Account not found.');
	});
    }

    /**
     * @test 
     * @group login
     * Unit test for a user that forgets its password.
     * The password for the account is incorrect.
     * @return void
     */
    public function login_forgotten_password()
    {
        $customer = factory(\App\Customer::class)->make([ 
             		'username' => 'testCustomer'
       	]);
	
	// Dummy test
	$this->browse(function ($browser) use ($customer) {
            $browser->visit('/login')  
		    ->assertPathIs('/login');
		    
	});
	// TODO Missing implementation	
	/*$this->browse(function ($browser) use ($customer) {
            $browser->visit('/')  
 		    ->clickLink('Forgot password')
		    ->assertPathIs('/password_reset')
		    ->type('testCustomer', 'username')
		    ->press('Reset password')
		    ->see('Check your email to reset your password.')
		    ->assertPathIs('/');
		    
	});*/

    }




}
