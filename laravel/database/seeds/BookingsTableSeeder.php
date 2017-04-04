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
        $today = Carbon::today();
        $hour = rand(9, 13);

        $noOfCustomers = \App\Customer::count();

        for($i=0; $i<10; $i++){
            $customer = rand(1, $noOfCustomers);
            $business = rand(1,3);

            Booking::create([
                'date' => $today->toDateString(),
                'start_time' => Carbon::createFromTime($hour, 0)->toTimeString(),
                'end_time' => Carbon::createFromTime($hour, 30)->toTimeString(),
                'customer_id' => $customer,
                'business_id' => $business,
            ])->save();

            $hour += rand(1, 2);
        }

    }

}
