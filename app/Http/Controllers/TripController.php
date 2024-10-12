<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTripRequest;
use App\Http\Requests\UpdateTripRequest;
use App\Http\Resources\TripResource;
use App\Models\Trip;
use App\Services\TripService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TripController extends BaseController
{
    public function __construct(TripService $service)
    {
        parent::__construct($service, TripResource::class);
    }

    public function filter(Request $request){
        $result = $this->service->filter($request);
        return $this->success('trips retrieved successfully', $this->resource::collection($result)->response()->getData(true));
    }

    public function create(CreateTripRequest $request){
        $result = $this->service->create($request->validated());
        return $this->success('trip created successfully', $this->resource::make($result));
    }

    public function update(UpdateTripRequest $request, Trip $trip){
        $result = $this->service->update($request->validated(), $trip);
        return $this->success('trip updated successfully', $this->resource::make($result));
    }

    public function delete(Trip $trip){
        $this->service->delete($trip);
        return $this->success('trip deleted successfully');
    }
}
