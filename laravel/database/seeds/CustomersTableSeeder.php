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
        Customer::create([
            'customer_name' => 'Grace',
            'username' => 'gracezzz',
            'password' => bcrypt('secret'),
            'email_address' => 's3558319@gmail.com',
            'mobile_phone' => '0411111111',
            'address' => '124 La Trobe St, Melbourne VIC 3000',
        ])->save();

        Customer::create([
            'customer_name' => 'Ervin',
            'username' => 'pancrisp',
            'password' => bcrypt('secret'),
            'email_address' => 's3577844@gmail.com',
            'mobile_phone' => '0422222222',
            'address' => '124 La Trobe St, Melbourne VIC 3000',
        ])->save();

        Customer::create([
            'customer_name' => 'Paulo',
            'username' => 'paulozf',
            'password' => bcrypt('secret'),
            'email_address' => 's3568672@gmail.com',
            'mobile_phone' => '0433333333',
            'address' => '124 La Trobe St, Melbourne VIC 3000',
        ])->save();
    }
}
