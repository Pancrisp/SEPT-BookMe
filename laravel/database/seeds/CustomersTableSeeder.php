<?php

use App\Customer;
use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * create 3 random entries for testing purposes
     *
     * @return void
     */
    public function run()
    {
        for($i=0; $i<3; $i++)
        {
            Customer::create([
                'customer_name' => str_random(10),
                'username' => str_random(10),
                'password' => bcrypt('secret'),
                'email_address' => str_random(10).'@gmail.com',
                'mobile_phone' => '04'.rand(0, 99999999),
                'address' => str_random(20),
            ])->save();
        }


    }
}
