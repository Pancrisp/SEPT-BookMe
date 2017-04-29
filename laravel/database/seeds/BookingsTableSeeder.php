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
        $numOfBusinesses = \App\Business::count();
        $numOfEmployees = \App\Employee::count();

        $typesOfSlots = [1,2,4];

        for($i=0; $i<5; $i++){
            $hour = rand(9, 11);
            $date = $date->addDay();

            for($j=0; $j<3; $j++){
                $empID = rand(1, $numOfEmployees);
                $cusID = rand(1, $numOfCustomers);
                $busID = rand(1, $numOfBusinesses);
                $business = \App\Business::find($busID);

                $slotKey = array_rand($typesOfSlots);
                $numOfSlots = $typesOfSlots[$slotKey];
                $activity = \App\Activity::where('num_of_slots', $numOfSlots)
                    ->where('business_id', $busID)
                    ->first();

                $startTime = Carbon::createFromTime($hour, 0);

                $booking = Booking::create([
                    'date' => $date->toDateString(),
                    'start_time' => $startTime->toTimeString(),
                    'activity' => $activity->activity_name,
                    'customer_id' => $cusID,
                    'business_id' => $busID,
                    'employee_id' => $empID,
                ]);

                for($k=0; $k<$numOfSlots; $k++)
                {
                    \App\Slot::create([
                        'slot_time' => $startTime->toTimeString(),
                        'booking_id' => $booking->booking_id
                    ]);

                    $startTime->addMinute($business->slot_period);
                }

                $hour += $numOfSlots;
                if($hour > 16)
                    break;
            }
        }
    }

}
