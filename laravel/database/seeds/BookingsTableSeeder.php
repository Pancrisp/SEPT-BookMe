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

        $numOfCustomers = \App\Customer::count();
        $numOfBuinesses = \App\Business::count();
        $numOfEmployees = \App\Employee::count();

        for($i=0; $i<5; $i++){
            $hour = rand(9, 13);
            $date = $date->addDay();

            Booking::create([
                'date' => $date->toDateString(),
                'start_time' => Carbon::createFromTime($hour, 0)->toTimeString(),
                'num_of_slots' => 2,
                'customer_id' => rand(1, $numOfCustomers),
                'business_id' => rand(1, $numOfBuinesses),
                'employee_id' => rand(1, $numOfEmployees),
            ])->save();

            $hour += rand(1, 2);

            Booking::create([
                'date' => $date->toDateString(),
                'start_time' => Carbon::createFromTime($hour, 0)->toTimeString(),
                'num_of_slots' => 1,
                'customer_id' => rand(1, $numOfCustomers),
                'business_id' => rand(1, $numOfBuinesses),
                'employee_id' => rand(1, $numOfEmployees),
            ])->save();

            $hour += rand(1, 2);

            Booking::create([
                'date' => $date->toDateString(),
                'start_time' => Carbon::createFromTime($hour, 0)->toTimeString(),
                'num_of_slots' => 4,
                'customer_id' => rand(1, $numOfCustomers),
                'business_id' => rand(1, $numOfBuinesses),
                'employee_id' => rand(1, $numOfEmployees),
            ])->save();
        }

    }

}
