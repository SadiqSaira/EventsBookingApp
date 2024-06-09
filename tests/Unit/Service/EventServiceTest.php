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

    public function test_it_can_get_events_without_search_filters()
    {
        // Resetting the database
        $this->refreshDatabase();

        // Make call to application...
        Event::factory()->count(5)->create();

        $this->assertDatabaseCount('events', 5);
    }

    public function test_it_can_get_events_with_search_by_date_filter()
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

    public function test_it_can_get_events_with_search_by_start_date_filter()
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

    public function test_it_can_get_events_with_search_by_country_filter()
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
    public function test_it_can_get_events_with_search_by_date_country_filter()
    {
        // Resetting the database
        $this->refreshDatabase();

        Event::factory()->create([
            'start_datetime' => '2024-06-02',
            'country' => 'USA',
        ]);
        Event::factory()->create([
            'start_datetime' => '2024-06-05',
            'country' => 'Canada',
        ]);
        Event::factory()->create([
            'start_datetime' => '2024-06-11',
            'country' => 'UK',
        ]);

        $request = new Request([
            'searchByDate' => '2024-06-01 to 2024-06-10',
            'searchByCountry' => 'USA']);

        $events = $this->eventService->getEvents($request);

        $this->assertCount(1, $events);

        $this->assertEquals('USA', $events->first()->country);
        $this->assertEquals('2024-06-02 00:00:00', $events->first()->start_datetime);

    }
    public function test_it_can_get_events_with_search_by_start_date_country_filter()
    {
        // Resetting the database
        $this->refreshDatabase();

        Event::factory()->create([
            'start_datetime' => '2024-06-02',
            'country' => 'USA',
        ]);
        Event::factory()->create([
            'start_datetime' => '2024-06-01',
            'country' => 'UK',
        ]);
        Event::factory()->create([
            'start_datetime' => '2024-06-11',
            'country' => 'UK',
        ]);

        $request = new Request([
            'searchByDate' => '2024-06-02',
            'searchByCountry' => 'UK']);

        $events = $this->eventService->getEvents($request);

        $this->assertCount(1, $events);

        $this->assertEquals('UK', $events->first()->country);
        $this->assertEquals('2024-06-11 00:00:00', $events->first()->start_datetime);

    }
}
