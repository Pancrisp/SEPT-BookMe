<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RostersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = Carbon::today();
        $noOfEmployees = \App\Employee::count();

        for($i=0; $i<7; $i++)
        {
            $employee1 = rand(1, $noOfEmployees);
            $employee2 = rand(1, $noOfEmployees);
            $date = $date->addDay();

            \App\Roster::create([
                'date' => $date->toDateString(),
                'shift' => 'Day',
                'employee_id' => $employee1
            ]);

            \App\Roster::create([
                'date' => $date->toDateString(),
                'shift' => 'Night',
                'employee_id' => $employee2
            ]);
        }
    }
}
