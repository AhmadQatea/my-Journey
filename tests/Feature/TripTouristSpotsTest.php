<?php

namespace Tests\Feature;

use App\Models\Governorate;
use App\Models\TouristSpot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TripTouristSpotsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->admin = User::factory()->create();
        $this->admin->assignRole('site_admin');
    }

    public function test_get_tourist_spots_by_governorates_requires_authentication(): void
    {
        $response = $this->getJson(route('admin.trips.tourist-spots.by-governorates'));

        $response->assertStatus(401);
    }

    public function test_get_tourist_spots_by_governorates_returns_empty_when_no_governorates_provided(): void
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->getJson(route('admin.trips.tourist-spots.by-governorates'));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'tourist_spots' => [],
                'count' => 0,
            ]);
    }

    public function test_get_tourist_spots_by_governorates_returns_spots_for_single_governorate(): void
    {
        $this->actingAs($this->admin, 'admin');

        // Create governorate
        $governorate = Governorate::factory()->create();

        // Create tourist spots
        $spot1 = TouristSpot::factory()->create(['governorate_id' => $governorate->id]);
        $spot2 = TouristSpot::factory()->create(['governorate_id' => $governorate->id]);

        $response = $this->getJson(route('admin.trips.tourist-spots.by-governorates', [
            'governorate_ids' => [$governorate->id]
        ]));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonCount(2, 'tourist_spots');

        $this->assertEquals($spot1->id, $response->json('tourist_spots.0.id'));
        $this->assertEquals($spot2->id, $response->json('tourist_spots.1.id'));
    }

    public function test_get_tourist_spots_by_governorates_returns_spots_for_multiple_governorates(): void
    {
        $this->actingAs($this->admin, 'admin');

        // Create governorates
        $gov1 = Governorate::factory()->create();
        $gov2 = Governorate::factory()->create();

        // Create tourist spots
        $spot1 = TouristSpot::factory()->create(['governorate_id' => $gov1->id]);
        $spot2 = TouristSpot::factory()->create(['governorate_id' => $gov2->id]);
        $spot3 = TouristSpot::factory()->create(['governorate_id' => $gov1->id]);

        $response = $this->getJson(route('admin.trips.tourist-spots.by-governorates', [
            'governorate_ids' => [$gov1->id, $gov2->id]
        ]));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonCount(3, 'tourist_spots');
    }

    public function test_get_tourist_spots_by_governorates_with_main_governorate_id(): void
    {
        $this->actingAs($this->admin, 'admin');

        $governorate = Governorate::factory()->create();
        $spot = TouristSpot::factory()->create(['governorate_id' => $governorate->id]);

        $response = $this->getJson(route('admin.trips.tourist-spots.by-governorates', [
            'main_governorate_id' => $governorate->id
        ]));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonCount(1, 'tourist_spots');
    }
}

