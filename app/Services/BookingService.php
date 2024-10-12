<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Models\Booking;
use App\Models\Trip;

class BookingService extends BaseService
{

    public function getOwnBookings(){
        $user = auth()->user();
        return Booking::query()->where('user_id', $user->id)->with('trip')->get();
    }

    public function create($data, $trip)
    {
        $user = auth()->user();

        $this->checkIfTripHasEnoughSeats($trip, $data['seats_booked']);

        $this->checkIfTripIsCompletedOrStarted($trip);

        //check if the user has already booked for this trip
        if (Booking::where('trip_id', $trip->id)->where('user_id', $user->id)->exists()){
            throw new CustomException(
                'you have already booked for this trip',
                ['trip' => 'you have already booked for this trip'],
                422);
        }


        $data['trip_id'] = $trip->id;
        $data['user_id'] = $user->id;

        $new_booking = Booking::create($data);

        $this->logAction('new booking created', [
            'user_id' => $user->id,
            'trip_id' => $trip->id,
            'seats_booked' => $new_booking->seats_booked
        ]);

        return $new_booking->load('trip');
    }

    public function update($data, $booking){

        $number_of_changed_seats = $data['seats_booked'] - $booking->seats_booked;
        if($number_of_changed_seats > 0){
            $this->checkIfTripHasEnoughSeats($booking->trip, $number_of_changed_seats);
        }

        $this->checkIfTripIsCompletedOrStarted($booking->trip);

        $booking->update([
            'seats_booked' => $data['seats_booked']
        ]);

        $this->logAction('booking was updated', [
            'user_id' => $booking->user_id,
            'booking_id' =>$booking->id,
            'new_seats' => $booking->seats_booked
        ]);

        return $booking->load('trip');
    }

    public function delete(Booking $booking){

        $this->checkIfTripIsCompletedOrStarted($booking->trip);

        $booking->delete();

        $this->logAction('booking was canceled', [
            'user_id' => $booking->user_id,
            'booking_id' => $booking->id,
        ]);
    }

    private function checkIfTripHasEnoughSeats(Trip $trip, $wanted_seats){
        $total_booked_seats = $trip->bookings->sum('seats_booked');
        $remaining_available_seats = $trip->available_seats - $total_booked_seats;

        if($remaining_available_seats < $wanted_seats){
            throw new CustomException(
                'no enough available seats',
                ['seats' => 'there are only '.$remaining_available_seats.' seats remaining'],
                422);
        }
    }

    private function checkIfTripIsCompletedOrStarted(Trip $trip){
        if ($trip->isCompleted()){
            throw new CustomException(
                'this trip has started already or completed',
                ['trip' => 'this trip has started already or completed'],
                422);
        }
    }
}
