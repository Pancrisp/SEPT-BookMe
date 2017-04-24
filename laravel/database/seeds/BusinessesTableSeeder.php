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
        ]);
    }
}
