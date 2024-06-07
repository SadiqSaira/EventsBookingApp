<?php

namespace Tests\Unit\Services;

use App\Models\Event;
use App\Services\EventService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class EventServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $eventService;

    public function setUp(): void
    {
        parent::setUp();

        $this->eventService = new EventService();
    }

    public function testItCanGetEventsWithoutSearchFilters()
    {
        // Resetting the database
        $this->refreshDatabase();

        // Make call to application...
        Event::factory()->count(5)->create();

        $this->assertDatabaseCount('events', 5);
    }

    public function testItCanGetEventsWithSearchByDateFilter()
    {
        // Resetting the database
        $this->refreshDatabase();

        Event::factory()->create([
            'start_datetime' => '2024-06-02',
        ]);
        Event::factory()->create([
            'start_datetime' => '2024-06-05',
        ]);
        Event::factory()->create([
            'start_datetime' => '2024-06-11',
        ]);

        $request = new Request(['searchByDate' => '2024-06-01 to 2024-06-10']);

        $events = $this->eventService->getEvents($request);

        $this->assertCount(2, $events);
    }

    public function testItCanGetEventsWithSearchByStartDateFilter()
    {
        // Resetting the database
        $this->refreshDatabase();

        Event::factory()->create([
            'start_datetime' => '2024-06-01',
        ]);
        Event::factory()->create([
            'start_datetime' => '2024-06-01',
        ]);
        Event::factory()->create([
            'start_datetime' => '2024-06-10',
        ]);

        $request = new Request(['searchByDate' => '2024-06-05']);

        $events = $this->eventService->getEvents($request);

        $this->assertCount(1, $events);
    }

    public function testItCanGetEventsWithSearchByCountryFilter()
    {
        // Resetting the database
        $this->refreshDatabase();

        Event::factory()->create([
            'country' => 'USA',
        ]);
        Event::factory()->create([
            'country' => 'Canada',
        ]);
        Event::factory()->create([
            'country' => 'UK',
        ]);

        $request = new Request(['searchByCountry' => 'USA']);

        $events = $this->eventService->getEvents($request);

        $this->assertCount(1, $events);
    }
}
