<?php

use App\Activity;
use App\Business;
use App\Employee;
use Illuminate\Database\Seeder;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * generate 10 random employees
     *
     * @return void
     */
    public function run()
    {
        // get the number of businesses
        $numOfBusinesses = Business::count();

        // generate 10 employees
        for($i=0;$i<10;$i++)
        {
            // randomly pick a business
            $businessID = rand(1, $numOfBusinesses);

            // randomly pick an activity of this business and get its id
            $activity = Activity::where('business_id', $businessID)
                ->inRandomOrder()
                ->first();
            $activityID = $activity->activity_id;

            // generate employees using factory faker
            $employee = factory(\App\Employee::class)->make([
                'TFN' => rand(100000000,999999999),
                'activity_id' => $activityID,
                'business_id' => $businessID
            ]);

            // create employee and save to BD
            Employee::create([
                'employee_name' => $employee->employee_name,
                'TFN' => $employee->TFN,
                'mobile_phone' => $employee->mobile_phone,
                'activity_id' => $employee->activity_id,
                'available_days' => $employee->available_days,
                'business_id' => $employee->business_id
            ]);
        }
    }
}
