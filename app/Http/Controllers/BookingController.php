<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Trip;
use App\Services\BookingService;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

class BookingController extends BaseController
{

    public function __construct(BookingService $service)
    {
        parent::__construct($service, BookingResource::class);
    }

    public function getOwnBookings(){
        $result = $this->service->getOwnBookings();
        $message = $result->isEmpty() ? 'you don\'t have any bookings yet' : 'Here are all your bookings';
        return $this->success($message, $this->resource::collection($result));
    }
    public function create(CreateBookingRequest $request, Trip $trip){

        $result = $this->service->create($request->validated(), $trip);
        return $this->success('seats booked successfully', $this->resource::make($result));
    }

    public function update(CreateBookingRequest $request, Booking $booking){

        $this->authorize('update', $booking);

        $result = $this->service->update($request->validated(), $booking);
        return $this->success('booking updated successfully', $this->resource::make($result));
    }

    public function delete(Booking $booking){
        $this->service->delete($booking);
        return $this->success('booking canceled successfully');
    }

}
