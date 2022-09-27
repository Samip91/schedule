<?php

namespace Database\Seeders;

use App\Models\Interval;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IntervalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Interval::create(['event_id' => 1, 'name' => 'Lunch break', 'start_time' => '12:00', 'end_time' => '13:00']);
        Interval::create(['event_id' => 1, 'name' => 'Cleaning break', 'start_time' => '15:00', 'end_time' => '16:00']);

        Interval::create(['event_id' => 2, 'name' => 'Lunch break', 'start_time' => '12:00', 'end_time' => '13:00']);
        Interval::create(['event_id' => 2, 'name' => 'Cleaning break', 'start_time' => '15:00', 'end_time' => '16:00']);
    }
}
