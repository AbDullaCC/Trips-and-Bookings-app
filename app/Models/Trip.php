<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Trip extends Model
{
    use HasFactory;
    use SoftDeletes;



    protected $fillable = [
        'destination',
        'price',
        'available_seats',
        'start_date',
        'end_date',
        'status'
    ];

    const COMPLETED = 'completed';
    const PENDING = 'pending';

    public function bookings(){
        return $this->hasMany(Booking::class);
    }

    public function scopeStatus(Builder $query, $status){
        if($status)
            $query->where('status', $status);
    }

    public function scopeDestination(Builder $query, $destination){
        if($destination)
            $query->where('destination', 'like', '%'.$destination.'%');
    }

    public function scopeStartsIn(Builder $query, $date){
        if ($date)
            $query->where('start_date', $date);
    }

    public function scopeEndsIn(Builder $query, $date){
        if ($date)
            $query->where('end_date', $date);
    }

    public function scopeAvailableSeats(Builder $query, $seats){
        if ($seats) {
            $query->select('trips.*', DB::raw('trips.available_seats - COALESCE(SUM(seats_booked),0) as remaining_seats'))
                ->leftJoin('bookings', 'trips.id', '=', 'bookings.trip_id')
                ->having('remaining_seats', '>=', $seats)
                ->groupBy('trips.id');
        }
    }

    public function isCompleted(){
        return $this->status == Trip::COMPLETED || $this->start_date <= now();
    }

}
