<?php

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'key' => 'membership_monthly',
            'value' => 100,
        ]);

        Setting::create([
            'key' => 'membership_daily',
            'value' => 20,
        ]);
    }
}
