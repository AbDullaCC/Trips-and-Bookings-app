<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookingCreationTest extends TestCase
{
use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_booking_fails_due_to_overbooking(): void
    {
        $trip = Trip::factory()->create(['available_seats' => 10]);
        Booking::create([
            'trip_id' => $trip->id,
            'user_id' => $this->user->id,
            'seats_booked' => 7
        ]);

        $url = "/api/users/trips/$trip->id/booking";

        $response = $this->actingAs($this->user)->post($url, [
            'seats_booked' => 4
        ]);
        $response->assertStatus(422);
        $response->assertJsonFragment(['message' => 'no enough available seats']);
    }

    public function test_booking_fails_due_to_date_conflict(){

        $trip = Trip::factory()->create(['start_date' => now()->subDay()]);

        $url = "/api/users/trips/$trip->id/booking";

        $response = $this->actingAs($this->user)->post($url, [
            'seats_booked' => 4
        ]);

        $response->assertJsonFragment(['message' => 'this trip has started already or completed']);
    }

    public function test_booking_created_successfully()
    {
        $trip = Trip::factory()->create();

        $url = "/api/users/trips/$trip->id/booking";

        $response = $this->actingAs($this->user)->post($url, [
            'seats_booked' => 1,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('bookings', [
            'user_id' => $this->user->id,
            'trip_id' => $trip->id,
            'seats_booked' => 1,
        ]);
    }
}
