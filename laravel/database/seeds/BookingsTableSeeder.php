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
        $hour = 9;

        for($i=0; $i<3; $i++){
            $customer = rand(0, 4);

            Booking::create([
                'date' => $today->toDateString(),
                'start_time' => Carbon::createFromTime($hour, 0)->toTimeString(),
                'end_time' => Carbon::createFromTime($hour, 30)->toTimeString(),
                'customer_id' => $customer,
                'business_id' => 1,
            ])->save();

            $hour += 2;
        }

    }

}
