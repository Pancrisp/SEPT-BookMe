<?php

use App\Business;
use App\User;
use Illuminate\Database\Seeder;

class BusinessesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $email = 'grace@gmail.com';

        $business = Business::create([
            'business_name' => 'Katsu King',
            'owner_name' => 'Grace',
            'username' => 'gracezzz',
            'email_address' => $email,
            'mobile_phone' => '0411222333',
            'address' => '124 La Trobe St, Melbourne VIC 3000',
            'slot_period' => 30
        ]);

        // create the user saving foreign key, this is for authentication
        User::create([
            'email'     => $email,
            'password' => bcrypt('secret'),
            'user_type' => 'business',
            'foreign_id' => $business->business_id
        ]);
    }
}
