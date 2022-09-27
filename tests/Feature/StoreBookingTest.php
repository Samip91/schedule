<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreBookingTest extends TestCase
{
    // use RefreshDatabase;

    public function test_previous_date_create()
    {
        $this->artisan('migrate:fresh --seed');
        $booking = Booking::factory()->make(['event_id' => 1, 'start_time' => '08:00', 'end_time' => '08:10', 'booking_date' => '2022-09-26']);
        $response = $this->postJson('api/bookings', [$booking->toArray()]);
        $response->assertStatus(422);
    }

    public function test_create_for_event_not_found()
    {
        $booking = Booking::factory()->make(['event_id' => 3, 'start_time' => '08:00', 'end_time' => '08:10', 'booking_date' => \date('Y-m-d', \strtotime('+1 day'))]);
        $response = $this->postJson('api/bookings', [$booking->toArray()]);
        $response->assertStatus(404);
    }

    public function test_create_for_out_of_date_range()
    {
        $date = date('Y-m-d', strtotime("+9 days"));
        $booking = Booking::factory()->make(['event_id' => 1, 'start_time' => '08:00', 'end_time' => '08:10', 'booking_date' => $date]);

        $response = $this->postJson('api/bookings', [$booking->toArray()]);
        $response->assertStatus(422);
    }

    public function test_create_in_buffer_time()
    {
        $date = date('Y-m-d', strtotime("+4 days"));
        $booking = Booking::factory()->make(['event_id' => 1, 'start_time' => '08:10', 'end_time' => '08:20', 'booking_date' => $date]);

        $response = $this->postJson('api/bookings', [$booking->toArray()]);
        $response->assertStatus(422);
    }

    public function test_create_for_more_than_max_client_per_slot()
    {
        $date = date('Y-m-d', strtotime("+4 days"));
        Booking::factory(3)->create(['event_id' => 1, 'start_time' => '08:10', 'end_time' => '08:20', 'booking_date' => $date]);

        $booking = Booking::factory()->make(['event_id' => 1, 'start_time' => '08:10', 'end_time' => '08:20', 'booking_date' => $date]);

        $response = $this->postJson('api/bookings', [$booking->toArray()]);
        $response->assertStatus(422);
    }

    public function test_create_for_sunday()
    {
        $booking = Booking::factory()->make(['event_id' => 1, 'start_time' => '08:10', 'end_time' => '08:20', 'booking_date' => '2022-10-02']);

        $response = $this->postJson('api/bookings', [$booking->toArray()]);
        $response->assertStatus(422);
    }

    public function test_create_before_opening()
    {
        $booking = Booking::factory()->make(['event_id' => 1, 'start_time' => '07:10', 'end_time' => '07:20', 'booking_date' => '2022-09-30']);

        $response = $this->postJson('api/bookings', [$booking->toArray()]);
        $response->assertStatus(422);
    }

    public function test_create_after_closing()
    {
        $booking = Booking::factory()->make(['event_id' => 1, 'start_time' => '20:00', 'end_time' => '20:10', 'booking_date' => '2022-09-30']);

        $response = $this->postJson('api/bookings', [$booking->toArray()]);
        $response->assertStatus(422);
    }

    public function test_create_to_check_break_time()
    {
        $booking = Booking::factory()->make(['event_id' => 1, 'start_time' => '12:00', 'end_time' => '12:10', 'booking_date' => '2022-09-30']);

        $response = $this->postJson('api/bookings', [$booking->toArray()]);
        $response->assertStatus(422);
    }

    public function test_create_to_check_public_holiday()
    {
        $booking = Booking::factory()->make(['event_id' => 1, 'start_time' => '11:00', 'end_time' => '11:10', 'booking_date' => '2022-09-29']);

        $response = $this->postJson('api/bookings', [$booking->toArray()]);
        $response->assertStatus(422);
    }

    public function test_create_for_multiple_event()
    {
        $booking = Booking::factory()->make(['event_id' => 1, 'start_time' => '11:00', 'end_time' => '11:10', 'booking_date' => '2022-09-30']);
        $response = $this->postJson('api/bookings', [$booking->toArray()]);
        $response->assertStatus(200);

        $bookingTwo = Booking::factory()->make(['event_id' => 2, 'start_time' => '10:20', 'end_time' => '11:20', 'booking_date' => '2022-09-30']);
        $response = $this->postJson('api/bookings', [$bookingTwo->toArray()]);
        $response->assertStatus(200);
    }

    public function test_create_three_booking_to_one_event_and_one_booking_to_secondEvent_at_same_time()
    {
        $booking = Booking::factory(3)->create(['event_id' => 1, 'start_time' => '8:00', 'end_time' => '8:10', 'booking_date' => '2022-10-03']);

        $bookingTwo = Booking::factory()->make(['event_id' => 2, 'start_time' => '8:00', 'end_time' => '9:00', 'booking_date' => '2022-10-03']);
        $response = $this->postJson('api/bookings', [$bookingTwo->toArray()]);
        $response->assertStatus(200);
    }

    public function test_create_invalid_start_time()
    {
        $bookingTwo = Booking::factory()->make(['event_id' => 1, 'start_time' => '17:05', 'end_time' => '17:15', 'booking_date' => '2022-10-30']);
        $response = $this->postJson('api/bookings', [$bookingTwo->toArray()]);
        $response->assertStatus(422);
    }
    public function test_create_invalid_end_time()
    {
        $bookingTwo = Booking::factory()->make(['event_id' => 1, 'start_time' => '17:00', 'end_time' => '17:15', 'booking_date' => '2022-10-30']);
        $response = $this->postJson('api/bookings', [$bookingTwo->toArray()]);
        $response->assertStatus(422);
    }

    public function test_create_start_time_inside_range_but_end_time_outside_range()
    {
        $booking = Booking::factory()->make(['event_id' => 2, 'start_time' => '19:30', 'end_time' => '20:30', 'booking_date' => '2022-09-30']);

        $response = $this->postJson('api/bookings', [$booking->toArray()]);
        $response->assertStatus(422);
    }

    public function test_create_invalid_date_format()
    {
        $booking = Booking::factory()->make(['event_id' => 2, 'start_time' => '9:10', 'end_time' => '10:20', 'booking_date' => '2022-09-35']);

        $response = $this->postJson('api/bookings', [$booking->toArray()]);
        $response->assertStatus(422);
    }

    public function test_create_invalid_email()
    {
        $booking = Booking::factory()->make(['email' => 'email', 'event_id' => 2, 'start_time' => '9:10', 'end_time' => '10:20', 'booking_date' => '2022-09-30']);

        $response = $this->postJson('api/bookings', [$booking->toArray()]);
        $response->assertStatus(422);
    }

    public function test_create_blank_data()
    {
        $response = $this->postJson('api/bookings', []);
        $response->assertStatus(422);
    }

    public function test_create_single_data_without_array()
    {
        $booking = Booking::factory()->make(['event_id' => 2, 'start_time' => '9:10', 'end_time' => '10:20', 'booking_date' => '2022-09-30']);

        $response = $this->postJson('api/bookings', $booking->toArray());
        $response->assertStatus(422);
    }
}
