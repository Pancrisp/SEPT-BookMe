<?php

use App\Activity;
use App\Booking;
use App\Business;
use App\Customer;
use App\Employee;
use App\Slot;
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

        $numOfCustomers = Customer::count();
        $numOfBusinesses = Business::count();
        $numOfEmployees = Employee::count();
        $numOfActivities = Activity::count();

        for($i=0; $i<5; $i++){
            $hour = rand(9, 11);
            $date = $date->addDay();

            for($j=0; $j<3; $j++){
                $empID = rand(1, $numOfEmployees);
                $cusID = rand(1, $numOfCustomers);
                $busID = rand(1, $numOfBusinesses);
                $actID = Employee::find($empID)->activity_id;
                $business = Business::find($busID);
                $activity = Activity::find($actID);
                $numOfSlots = $activity->num_of_slots;

                $startTime = Carbon::createFromTime($hour, 0);

                $booking = Booking::create([
                    'date' => $date->toDateString(),
                    'start_time' => $startTime->toTimeString(),
                    'customer_id' => $cusID,
                    'business_id' => $busID,
                    'employee_id' => $empID,
                ]);

                for($k=0; $k<$numOfSlots; $k++)
                {
                    Slot::create([
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
