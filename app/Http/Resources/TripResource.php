<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'destination' => $this->destination,
            'price' => $this->price,
            'status' => $this->status,
            'total_seats' => $this->available_seats,
            'available_seats' => $this->available_seats - $this->bookings()->sum('seats_booked'),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];
    }
}
