<?php

use App\Business;
use App\BusinessHour;
use Illuminate\Database\Seeder;

class BusinessHoursTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seed business hours for each business in DB
     *
     * @return void
     */
    public function run()
    {
        // initialise all the days
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

        // count number of existing business in DB
        $numOfBusinesses = Business::count();

        // creating business hours for each business
        for($i=0;$i<$numOfBusinesses;$i++)
        {
            // creating business hour everyday
            foreach($days as $day)
                BusinessHour::create([
                    'business_id'  => $i+1,
                    'day'          => $day,
                    'opening_time' => '09:00',
                    'closing_time' => '17:00'
                ]);
        }
    }
}
