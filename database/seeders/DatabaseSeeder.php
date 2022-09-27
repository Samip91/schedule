<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(EventSeeder::class);
        $this->call(TimeSlotSeeder::class);
        $this->call(IntervalSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(LeaveSeeder::class);
    }
}
