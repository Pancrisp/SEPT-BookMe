<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CustomerBookingTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->browse(function ($browser) {
            $browser->visit('/')
                    ->assertSee('Laravel');
        });
    }
	/**
     * Session is considered as an authenticated user
     * Test for a successful customer booking.
     * @return void
     * TODO Revise table and time selection. 
     */
    public function successfulBooking()
    {
        $user = factory(App\User::class)->create([
             'email' => 'copsicus@sept.com', 
             'password' => bcrypt('copsicus123')
        ]);

        $this->actingAs($user)
            ->withSession(['sept' => 'test'])
	    ->visit('/booking')
	    ->select(date("Y-m-d"),'bookingDate')
	    ->select('table1','table')            
	    ->press('Start Time')
	    ->press('End Time')
	    ->press('Book')
            ->see('Table booked successfully')
            ->onPage('/booking');
    }

    /**
     * Test for an unauthorised customer attempting to 
     * make a booking.
     * @return void
     */
    public function unauthorisedBooking()
    {
        $user = factory(App\User::class)->create([
             'email' => 'copsicus@sept.com', 
             'password' => bcrypt('copsicus123')
        ]);

        $this->actingAs($user)
	    ->visit('/booking')
	    ->seePageIs('/login')	    
	    ->see('Please login first.');

    }

    /**
     * Test for an invalid customer attempt to make 
     * a booking.
     * @return void
     */
    public function invalidBooking()
    {
	
	$user = factory(App\User::class)->create([
             'email' => 'copsicus@sept.com', 
             'password' => bcrypt('copsicus123')
        ]);
	
	/*successful booking*/	
        $this->actingAs($user)
            ->withSession(['sept' => 'test'])
	    ->visit('/booking')
	    ->select(date("Y-m-d"),'bookingDate')
	    ->select('table1','table')            
	    ->press('Start Time')
	    ->press('End Time')
	    ->press('Book')
            ->see('Table booked successfully')
            ->onPage('/booking');

	/*Attempting to book the same table at the same time*/

        $this->actingAs($user)
	    ->withSession(['sept' => 'test'])
	    ->visit('/booking')
	    ->select(date("Y-m-d"),'bookingDate')
	    ->select('table1','table')            
	    ->press('Start Time')
	    ->press('End Time')
	    ->press('Book')
            ->see('Table unavailable')
            ->onPage('/booking');

    }
}
