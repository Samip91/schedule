<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create(['event_id' => 1, 'key' => 'max_days_available', 'value' => 7]);
        Setting::create(['event_id' => 1, 'key' => 'max_client_per_slot', 'value' => 3]);

        Setting::create(['event_id' => 2, 'key' => 'max_days_available', 'value' => 7]);
        Setting::create(['event_id' => 2, 'key' => 'max_client_per_slot', 'value' => 3]);
    }
}
