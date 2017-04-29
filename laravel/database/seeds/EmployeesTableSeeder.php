<?php

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
        // generate 10 employees
        for($i=0;$i<10;$i++)
        {
            // generate employees using factory faker
            $employee = factory(\App\Employee::class)->make([
                'TFN' => rand(100000000,999999999)
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
