<?php

use App\Booking;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = Carbon::today();

        $noOfCustomers = \App\Customer::count();

        for($i=0; $i<5; $i++){
            $hour = rand(9, 13);
            $date = $date->addDay();

            Booking::create([
                'date' => $date->toDateString(),
                'start_time' => Carbon::createFromTime($hour, 0)->toTimeString(),
                'end_time' => Carbon::createFromTime($hour, 30)->toTimeString(),
                'customer_id' => rand(1, $noOfCustomers),
                'business_id' => rand(1,3),
            ])->save();

            $hour += rand(1, 2);

            Booking::create([
                'date' => $date->toDateString(),
                'start_time' => Carbon::createFromTime($hour, 0)->toTimeString(),
                'end_time' => Carbon::createFromTime($hour, 30)->toTimeString(),
                'customer_id' => rand(1, $noOfCustomers),
                'business_id' => rand(1,3),
            ])->save();

            $hour += rand(1, 2);

            Booking::create([
                'date' => $date->toDateString(),
                'start_time' => Carbon::createFromTime($hour, 0)->toTimeString(),
                'end_time' => Carbon::createFromTime($hour, 30)->toTimeString(),
                'customer_id' => rand(1, $noOfCustomers),
                'business_id' => rand(1,3),
            ])->save();
        }

    }

}
