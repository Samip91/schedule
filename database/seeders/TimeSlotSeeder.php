<?php

namespace Database\Seeders;

use App\Models\Timeslot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimeSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Timeslot::create(['event_id' => 1, 'day' => 3, 'opening_time' => '08:00', 'closing_time' => '20:00', 'slot_time' => 10, 'buffer_time' => 5]);
        Timeslot::create(['event_id' => 1, 'day' => 4, 'opening_time' => '08:00', 'closing_time' => '20:00', 'slot_time' => 10, 'buffer_time' => 5]);
        Timeslot::create(['event_id' => 1, 'day' => 5, 'opening_time' => '08:00', 'closing_time' => '20:00', 'slot_time' => 10, 'buffer_time' => 5]);
        Timeslot::create(['event_id' => 1, 'day' => 6, 'opening_time' => '10:00', 'closing_time' => '22:00', 'slot_time' => 10, 'buffer_time' => 5]);
        Timeslot::create(['event_id' => 1, 'day' => 1, 'opening_time' => '08:00', 'closing_time' => '20:00', 'slot_time' => 10, 'buffer_time' => 5]);
        Timeslot::create(['event_id' => 1, 'day' => 2, 'opening_time' => '08:00', 'closing_time' => '20:00', 'slot_time' => 10, 'buffer_time' => 5]);

        Timeslot::create(['event_id' => 2, 'day' => 3, 'opening_time' => '08:00', 'closing_time' => '20:00', 'slot_time' => 60, 'buffer_time' => 10]);
        Timeslot::create(['event_id' => 2, 'day' => 4, 'opening_time' => '08:00', 'closing_time' => '20:00', 'slot_time' => 60, 'buffer_time' => 10]);
        Timeslot::create(['event_id' => 2, 'day' => 5, 'opening_time' => '08:00', 'closing_time' => '20:00', 'slot_time' => 60, 'buffer_time' => 10]);
        Timeslot::create(['event_id' => 2, 'day' => 6, 'opening_time' => '10:00', 'closing_time' => '22:00', 'slot_time' => 60, 'buffer_time' => 10]);
        Timeslot::create(['event_id' => 2, 'day' => 1, 'opening_time' => '08:00', 'closing_time' => '20:00', 'slot_time' => 60, 'buffer_time' => 10]);
        Timeslot::create(['event_id' => 2, 'day' => 2, 'opening_time' => '08:00', 'closing_time' => '20:00', 'slot_time' => 60, 'buffer_time' => 10]);
    }
}
