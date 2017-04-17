<?php

use App\Business;
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
        Business::create([
            'business_name' => 'Katsu King',
            'owner_name' => 'Grace',
            'username' => 'gracezzz',
            'password' => bcrypt('secret'),
            'email_address' => 's3558319@gmail.com',
            'mobile_phone' => '0411111111',
            'address' => '124 La Trobe St, Melbourne VIC 3000',
            'slot_period' => 30
        ])->save();

        Business::create([
            'business_name' => 'No.1 Delicious',
            'owner_name' => 'Ervin',
            'username' => 'pancrisp',
            'password' => bcrypt('secret'),
            'email_address' => 's3577844@gmail.com',
            'mobile_phone' => '0422222222',
            'address' => '124 La Trobe St, Melbourne VIC 3000',
            'slot_period' => 45
        ])->save();

        Business::create([
            'business_name' => 'Sushi Sushi',
            'owner_name' => 'Paulo',
            'username' => 'paulozf',
            'password' => bcrypt('secret'),
            'email_address' => 's3568672@gmail.com',
            'mobile_phone' => '0433333333',
            'address' => '124 La Trobe St, Melbourne VIC 3000',
            'slot_period' => 60
        ])->save();
    }
}
