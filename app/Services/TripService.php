<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Http\Resources\TripResource;
use App\Models\Booking;
use App\Models\Trip;
use Illuminate\Support\Facades\DB;

class TripService extends BaseService
{

    public function filter($request){

        $trips = Trip::query()
            ->availableSeats($request->available_seats)
            ->status($request->status)
            ->destination($request->destination)
            ->startsIn($request->starts_in)
            ->endsIn($request->ends_in);

        return $trips->paginate(10);
    }

    public function create($data){
        $trip = Trip::create($data);
        $this->logAction('new trip created', [
            'admin_user_id' => auth()->user()->id,
            'trip' => TripResource::make($trip)
        ]);

        return $trip;
    }

    public function update($data, $trip){

        $total_booked_seats = $trip->bookings->sum('seats_booked');
        if ($total_booked_seats > $data['available_seats']){
            throw new CustomException(
                'invalid seats number',
                ['seats' => 'the new total seats number is less than the already booked seats number'],
                422);
        }

        $trip->update($data);

        $this->logAction('trip was updated', [
            'admin_user_id' => auth()->user()->id,
            'trip_id' => $trip->id,
            'trip_updated_attributes' => $trip->getChanges()
        ]);

        return $trip;
    }

    public function delete(Trip $trip){
        foreach ($trip->bookings as $booking){
            $booking->delete();
            $this->logAction('booking was deleted due to trip deletion', [
                'admin_user_id' => auth()->user()->id,
                'trip_id' => $trip->id,
                'booking_id' => $booking->id,
            ]);
        }
        $trip->delete();

        $this->logAction('trip was deleted', [
            'admin_user_id' => auth()->user()->id,
            'trip_id' => $trip->id,
        ]);
    }

}
