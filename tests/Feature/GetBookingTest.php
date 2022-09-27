<?php

namespace Tests\Feature;

use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function PHPUnit\Framework\assertJson;

class GetBookingTest extends TestCase
{

    public function test_get_all_data()
    {
        $this->artisan('migrate:fresh --seed');
        $response = $this->getJson('api/bookings');
        $response->assertStatus(200);
    }

    public function test_after_one_data_inserted()
    {
        $booking = Booking::factory()->create(['event_id' => 1, 'start_time' => '8:00', 'end_time' => '8:10', 'booking_date' => '2022-10-03']);
        $response = $this->getJson('api/bookings');
        $response->assertStatus(200);
    }

    public function test_after_three_data_inserted_one_same_time()
    {
        $booking = Booking::factory(3)->create(['event_id' => 1, 'start_time' => '8:00', 'end_time' => '8:10', 'booking_date' => '2022-09-30']);
        $response = $this->getJson('api/bookings');
        $response->assertStatus(200);
    }
}
