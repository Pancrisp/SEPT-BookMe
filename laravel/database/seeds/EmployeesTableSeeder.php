<?php

use App\Employee;
use Illuminate\Database\Seeder;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * create 5 random entries for testing purposes
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<5;$i++)
        {
            $employee = factory(\App\Employee::class)->make([
                'TFN' => rand(100000000,999999999)
            ]);

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
