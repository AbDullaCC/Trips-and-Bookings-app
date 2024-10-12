<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use function PHPUnit\Framework\assertTrue;

class TripSearchAndPaginationTest extends TestCase
{
    use RefreshDatabase;

    private User $user ;
    private $url = '/api/users/trips/filter';

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_pagination_works_with_trips()
    {
        Trip::factory(15)->create();

        $response = $this->actingAs($this->user)->get($this->url.'?page=2');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'data',
                'links',
                'meta',
            ]
        ]);
        $response->assertJsonCount(5, 'data.data');
    }

    public function test_destination_search_works(){

        $this->createTripsForTesting();

        $response = $this->actingAs($this->user)->get($this->url.'?destination=alep');

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data.data');
        $response->assertJsonFragment(['destination' => 'Aleppo']);
    }

    public function test_date_filters_works(){

        $this->createTripsForTesting();

        $response = $this->actingAs($this->user)->get($this->url.'?starts_in=2023-11-01');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data.data');
        $response->assertJsonFragment(['start_date' => '2023-11-01']);

        $response = $this->actingAs($this->user)->get($this->url.'?ends_in=2023-11-04');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data.data');
        $response->assertJsonFragment(['end_date' => '2023-11-04']);
    }


    public function test_status_filter_works(){

        $this->createTripsForTesting();

        $response = $this->actingAs($this->user)->get($this->url.'?status='.Trip::COMPLETED);

        $response->assertStatus(200);
        $response->assertJsonMissing(['status' => 'pending']);
    }

    public function test_available_seats_filter_works(){

        $trip = $this->createTripsForTesting();
        $seats_filter = 12;

        Booking::create([
            'trip_id' => $trip->id,
            'user_id' => $this->user->id,
            'seats_booked' => 4
        ]);

        $response = $this->actingAs($this->user)->get($this->url.'?available_seats='.$seats_filter);

        $response->assertStatus(200);
        $response->assertJsonFragment(['available_seats' => 13]);

        $trips = $response->json()['data']['data'];
        foreach ($trips as $trip){
            $this->assertGreaterThanOrEqual($seats_filter, $trip['available_seats']);
        }
    }

    public function test_no_filters_works_fine_and_bring_all_trips(){

        $trip = $this->createTripsForTesting();
        $seats_filter = 12;

        $response = $this->actingAs($this->user)->get($this->url);

        $response->assertStatus(200);
        $response->assertJsonPath('data.meta.total', 14);

    }


    private function createTripsForTesting(){
        Trip::factory(4)->create(['available_seats' => 1 ,'status' => Trip::COMPLETED]);
        Trip::factory(3)->create(['start_date' => '2023-11-1']);
        Trip::factory(2)->create(['end_date' => '2023-11-4']);
        $trips = Trip::factory(5)->create(['available_seats' => 17, 'destination' => 'Aleppo']);
        return $trips->first();
    }
}
