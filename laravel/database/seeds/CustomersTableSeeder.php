<?php

use App\Customer;
use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
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
            $customer = factory(\App\Customer::class)->make([
                'password' => bcrypt('secret')
            ]);

            Customer::create([
                'customer_name' => $customer->customer_name,
                'username' => $customer->username,
                'password' => $customer->password,
                'email_address' => $customer->email_address,
                'mobile_phone' => $customer->mobile_phone,
                'address' => $customer->address,
            ]);
        }
    }
}
