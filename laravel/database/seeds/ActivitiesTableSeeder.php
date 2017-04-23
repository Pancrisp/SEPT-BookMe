<?php

use App\Activity;
use Illuminate\Database\Seeder;

class ActivitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Activity::create([
            'activity_name' => 'Buffet',
            'num_of_slots'  => 4,
            'business_id'   => 1,
        ])->save();

        Activity::create([
            'activity_name' => 'Normal',
            'num_of_slots'  => 2,
            'business_id'   => 1,
        ])->save();

        Activity::create([
            'activity_name' => 'Express',
            'num_of_slots'  => 1,
            'business_id'   => 1,
        ])->save();
    }
}
