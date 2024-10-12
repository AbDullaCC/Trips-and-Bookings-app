<?php

namespace Tests\Unit;

use App\Http\Requests\CreateTripRequest;
use App\Models\Trip;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class TripCreationValidationTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_the_trip_creation_validation_fails(): void
    {
        $data = [
            'destination' => 'Aleppo',
            'price' => '-500',
            'available_seats' => 'ten',
            'start_date' => '2024-11-1',
            'end_date' => '2024-13-11',
            'status' => 'waiting',
        ];

        $request = new CreateTripRequest();

        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertEquals(
            ['price', 'available_seats', 'end_date', 'status'],
            array_keys($validator->errors()->toArray())
        );
    }

    public function test_the_trip_creation_validation_passes(): void
    {
        $data = [
            'destination' => 'Aleppo',
            'price' => '150000',
            'available_seats' => '20',
            'start_date' => '2024-11-1',
            'end_date' => '2024-11-4',
            'status' => Trip::PENDING,
        ];

        $request = new CreateTripRequest();

        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->fails());

    }
}
