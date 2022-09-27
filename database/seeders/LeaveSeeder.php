<?php

namespace Database\Seeders;

use App\Models\Leave;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Leave::create(['event_id' => 1, 'date' => '2022-09-29']);
        Leave::create(['event_id' => 2, 'date' => '2022-09-29']);
    }
}
