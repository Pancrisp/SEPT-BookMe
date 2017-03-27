<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AddEmployeeTest extends DuskTestCase
{
    /**
     * Add employee successfully.
     *
     * @return void
     */
    public function testAddEmployeeSuccessful()
    {// TODO retrieve business owner user
	$user = factory(\App\Businesses::class)->make([
	     'username' => 'copsicus', 
             'password' => bcrypt('copsicus123')
        ]);
         $this->browse(function ($browser) {
            $browser->loginAs(Businesses::find(1))
		->visit('/dashboard')      
		->type('employeeName','copsiccus')
            	->type('employeeTFN', '12345678')
	    	->type('employeeAddress','copsiccus. Melbourne')
            	->type('employeePhone', '12345678')
            	->press('add')
            	->assertSee('Employee added');
            	
	});
    }
     
     /**
     * Add employee not successfully, existing TFN
     *
     * @return void
     */
    public function testAddEmployeeUnSuccessfulTFN()
    {// TODO retrieve business owner user
	$user = factory(\App\Employee::class)->make([
	     'employeeName' => 'copsicus', 
             'employeeTFN' => '12345678'
        ]);
         $this->browse(function ($browser) {
            $browser->loginAs(Businesses::find(1))
		->visit('/dashboard')      
		->type('employeeName','copsiccus')
            	->type('employeeTFN', '12345678')
	    	->type('employeeAddress','copsiccus. Melbourne')
            	->type('employeePhone', '12345678')
            	->press('add')
            	->assertSee('Duplicate Employee TFN');
            	
	});
    }
	
 /**
     * Add employee not successfully, unauthenticated business owner
     *
     * @return void
     */
    public function testAddEmployeeUnSuccessfulAnonymousOwner()
    {
	$user = factory(\App\Employee::class)->make([
	     'employeeName' => 'copsicus', 
             'employeeTFN' => '12345678'
        ]);
         $this->browse(function ($browser) {
            $browser->visit('/dashboard')      
		->type('employeeName','copsiccus')
            	->type('employeeTFN', '12345678')
	    	->type('employeeAddress','copsiccus. Melbourne')
            	->type('employeePhone', '12345678')
            	->press('add')
		->visit('/')
            	->assertSee('Please, login first');
            	
	});
    }
       
}
