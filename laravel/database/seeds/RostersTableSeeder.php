<?php

use App\Activity;
use App\Employee;
use App\Roster;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RostersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * generate roster for next 7 days
     * roster one employee for each activity
     *
     * @return void
     */
    public function run()
    {
        // get today's date
        $date = Carbon::today(new DateTimeZone('Australia/Melbourne'));
        // get activities from DB
        $activities = Activity::all();

        // roster for next week
        for($i=0; $i<7; $i++)
        {
            // roster starts from tomorrow and increment a day in each iteration
            $date = $date->addDay();

            // roster an employee for each activity
            foreach ($activities as $activity)
            {
                // select a random employee who's in charge of this activity from DB
                $employee = Employee::where('activity_id', $activity->activity_id)
                    ->inRandomOrder()
                    ->first();

                // create and save this roster
                Roster::create([
                    'date' => $date->toDateString(),
                    'employee_id' => $employee->employee_id
                ]);
            }
        }
    }
}
