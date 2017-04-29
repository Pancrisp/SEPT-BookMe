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
     * create bookings based on random values
     * create slots based on bookings created
     *
     * generate bookings for next 5 days
     * max 3 bookings per day
     *
     * @return void
     */
    public function run()
    {
        // get today's date
        $date = Carbon::today();

        // get number of some elements from DB
        $numOfCustomers = Customer::count();
        $numOfBusinesses = Business::count();
        $numOfEmployees = Employee::count();

        // create bookings for the next 5 days
        for($i=0; $i<5; $i++)
        {
            // randomly pick a starting time for the 1st booking
            $hour = rand(9, 11);
            // increment 1 day in each iteration
            $date = $date->addDay();

            // create max 3 bookings for a day
            for($j=0; $j<3; $j++)
            {
                // randomly pick employee, customer, business
                $empID = rand(1, $numOfEmployees);
                $cusID = rand(1, $numOfCustomers);
                $busID = rand(1, $numOfBusinesses);

                // get attributes needed based on the random inputs
                $actID = Employee::find($empID)->activity_id;
                $business = Business::find($busID);
                $activity = Activity::find($actID);
                $numOfSlots = $activity->num_of_slots;

                // carbon the start time, prepare for calculation later
                $startTime = Carbon::createFromTime($hour, 0);

                // create and save booking to DB
                $booking = Booking::create([
                    'date' => $date->toDateString(),
                    'start_time' => $startTime->toTimeString(),
                    'customer_id' => $cusID,
                    'business_id' => $busID,
                    'employee_id' => $empID,
                ]);

                // create slots according to booking created
                for($k=0; $k<$numOfSlots; $k++)
                {
                    // save to DB
                    Slot::create([
                        'slot_time' => $startTime->toTimeString(),
                        'booking_id' => $booking->booking_id
                    ]);

                    // increment based on config pulled from DB
                    $startTime->addMinute($business->slot_period);
                }

                // increment the hour value and break the loop if it's after hour
                $hour += $numOfSlots;
                if($hour > 16)
                    break;
            }
        }
    }

}
