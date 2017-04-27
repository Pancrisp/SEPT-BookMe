<?php

use App\Customer;
use App\User;
use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * create 5 customers for testing purposes
     * entries are randomly generated using factory faker
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<5;$i++)
        {
            $customerFaker = factory(\App\Customer::class)->make([
                'password' => bcrypt('secret')
            ]);

            $customer = Customer::create([
                'customer_name' => $customerFaker->customer_name,
                'username' => $customerFaker->username,
                'email_address' => $customerFaker->email_address,
                'mobile_phone' => $customerFaker->mobile_phone,
                'address' => $customerFaker->address,
            ]);

            // create the user saving foreign key, this is for authentication
            User::create([
                'email'     => $customerFaker->email_address,
                'password' => $customerFaker->password,
                'user_type' => 'customer',
                'foreign_id' => $customer->customer_id
            ]);
        }
    }
}
