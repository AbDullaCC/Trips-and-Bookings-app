<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'trip' => TripResource::make($this->whenLoaded('trip')),
            'user' => UserResource::make($this->whenLoaded('user')),
            'seats_booked' => $this->seats_booked,
            'total_price' => $this->trip->price * $this->seats_booked
        ];
    }
}
