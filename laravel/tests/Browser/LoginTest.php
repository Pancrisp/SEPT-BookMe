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
     *	Unit test for a successful user log in.
     *
     *  @return void
     */
    public function testLoginSuccessful()
    {
        // Retrieving an existing customer		
	$customer = \App\Customer::where('customer_id',1)->first();

        $this->browse(function ($browser) use ($customer) {
            $browser->visit('/')    
		    ->type('username',$customer->username)
		    ->type('password', 'secret')
		    ->radio('usertype', 'customer' )    
		    ->press('login')
		    ->assertPathIs('/dashboard')   
		    ->assertSee('Hello, '.$customer->customer_name);
	});
    }

   /**
     * @group accepted
     * @group login
     * Unit test for an  unsuccessful user log in.
     * The password for the account is incorrect.
     * @return void
     */
    public function testLoginIncorrectPassword()
    {
        
	// Retrieving an existing customer		
	$customer = \App\Customer::where('customer_id',2)->first();

        $this->browse(function ($browser) use ($customer) {
            $browser->visit('/')   
		    ->type('username',$customer->username)
		    ->type('password', 'other_password')
		    ->radio('usertype', 'customer' )    
		    ->press('login')
		    ->assertPathIs('/')
		    ->assertSee('Incorrect password. Please try again!');
	});

    }

    /**
     * @group accepted
     * @group login
     * Unit test for an  unsuccessful user log in.
     * The emai/username account is misspelled 
     * @return void
     */
    public function testLoginMisspelledUsername()
    {
        $customer = factory(\App\Customer::class)->make([ 
             		'username' => 'testCustomerr'
       	]);

	$this->browse(function ($browser) use ($customer) {
            $browser->visit('/')   
		    ->type('username',$customer->username)
		    ->type('password', 'copsicus123')
		    ->radio('usertype', 'customer' )    
		    ->press('login')
		    ->assertPathIs('/')
		    ->assertSee('Account not found.');
	});
    }

    /**
     * @group accepted
     * @group login
     * Unit test for an  unsuccessful user log in.
     * The emai/username account does not exist
     * @return void
     */
    public function testLoginUnexistingUsername()
    {
	
        $this->browse(function ($browser) {
            $browser->visit('/')   
		    ->type('username','unexisting_customer')
		    ->type('password', 'copsicus123')
		    ->radio('usertype', 'customer' )    
		    ->press('login')
		    ->assertPathIs('/')
		    ->assertSee('Account not found.');
	});
    }

    /**
     * @group login
     * Unit test for a user that forgets its password.
     * The password for the account is incorrect.
     * @return void
     */
    public function testLoginForgottenPassword()
    {
        $customer = factory(\App\Customer::class)->make([ 
             		'username' => 'testCustomer'
       	]);
	
	// Dummy test
	$this->browse(function ($browser) use ($customer) {
            $browser->visit('/')  
		    ->assertPathIs('/');
		    
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
