<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Trip::all() as $trip){
            $users_ids = User::inRandomOrder()->take(rand(3,5))->pluck('id');
            $remaining_seats = $trip->available_seats;

            foreach ($users_ids as $user_id){
                $seats_booked = rand(1, $remaining_seats/count($users_ids));
                Booking::create([
                    'trip_id' => $trip->id,
                    'user_id' => $user_id,
                    'seats_booked' => $seats_booked,
                ]);
                $remaining_seats = $remaining_seats - $seats_booked;
            }
        }
    }
}
