<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Http\Controllers\EventController;
use App\Services\EventServiceInterface;
use Illuminate\Http\Request;
use Mockery;
use App\Models\Event;
use App\Http\Resources\EventResource;
use App\Http\Requests\Event\BookEventRequest;

class EventControllerTest extends TestCase
{
    
    // Ensure you are using this trait

    protected $eventServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the EventServiceInterface
        $this->eventServiceMock = Mockery::mock(EventServiceInterface::class);
        $this->app->instance(EventServiceInterface::class, $this->eventServiceMock);
    }

    public function test_show_event_by_id()
    {
        $event =  new Event([
            'event_name' => 'Event 1',
            'start_datetime' => '2024-06-05',
            'end_datetime' => '2024-06-06',
            'city' => 'New York',
            'country' => 'USA',
            'ticket_allocation' => 100,
            'max_tickets_per_customer' => 5
        ]);
        $request = new Request(['eventId' => 1]);

        $this->eventServiceMock
        ->shouldReceive('getEventById')
        ->once()
        ->with(1)
        ->andReturn($event);

        $controller = new EventController($this->eventServiceMock);

        $response = $controller->show($request);

        // Convert Inertia response to an HTTP response
        $responsenew = $response->toResponse($request);

        $this->assertEquals(200, $responsenew->getStatusCode());

        // get the page data from the original property
        $responseData = $responsenew->original;
        $page = $responseData['page'];
    
        $this->assertEquals('Event/BookEvent', $page['component']);
    
        // Assert the eventId is correctly passed in the response
        $this->assertEquals(1, $page['props']['eventId']);
    
        // Assert the event data matches the expected data
        $this->assertEquals(
            (new EventResource($event))->response()->getData(true),
            $page['props']['events']
        );
    }
    public function test_get_all_events_when_no_filter()
    {
        $events = collect([
            new Event([
                'event_name' => 'Event 1',
                'start_datetime' => '2024-06-05',
                'end_datetime' => '2024-06-06',
                'city' => 'New York',
                'country' => 'USA',
                'ticket_allocation' => 100,
                'max_tickets_per_customer' => 5
            ]),
            new Event([
                'event_name' => 'Event 2',
                'start_datetime' => '2024-06-10',
                'end_datetime' => '2024-06-11',
                'city' => 'Los Angeles',
                'country' => 'USA',
                'ticket_allocation' => 100,
                'max_tickets_per_customer' => 5
            ]),
            new Event([
                'event_name' => 'Event 3',
                'start_datetime' => '2024-06-20',
                'end_datetime' => '2024-06-21',
                'city' => 'Chicago',
                'country' => 'Canada',
                'ticket_allocation' => 100,
                'max_tickets_per_customer' => 5
            ]),
            new Event([
                'event_name' => 'Event 4',
                'start_datetime' => '2024-05-01',
                'end_datetime' => '2024-05-02',
                'city' => 'Houston',
                'country' => 'Mexico',
                'ticket_allocation' => 100,
                'max_tickets_per_customer' => 5
            ]),
            new Event([
                'event_name' => 'Event 5',
                'start_datetime' => '2024-07-01',
                'end_datetime' => '2024-07-02',
                'city' => 'Phoenix',
                'country' => 'Brazil',
                'ticket_allocation' => 100,
                'max_tickets_per_customer' => 5
            ]),
        ]);
        
        // Create a request with search parameters
        $request = new Request();
        $incomingFields =[];
        
        $this->eventServiceMock
        ->shouldReceive('getEvents')
        ->once()
        ->with($incomingFields) 
        ->andReturn($events);
        

        // Create an instance of the controller 
        $controller = new EventController($this->eventServiceMock);

        
        // Call the index method and get the response
        $response = $controller->index($request);
        
        $responsenew = $response->toResponse($request);

        // Assert that the response status is 200
        $this->assertEquals($responsenew->getStatusCode() , 200);

        $responseData =  $responsenew->original;
        $page = $responseData['page'];

        $this->assertEquals('Event/Index', $page['component']);
        
        //response has 5 events based on date search
        $this->assertCount(5, $page['props']['events']['data']);
        $this->assertEquals(EventResource::collection($events)->response()->getData(true), 
                             $page['props']['events']);

    }
    public function test_filter_events_on_date_range()
    {
        $events = collect([
            new Event([
                'event_name' => 'Event 1',
                'start_datetime' => '2024-06-05',
                'end_datetime' => '2024-06-06',
                'city' => 'New York',
                'country' => 'USA',
                'ticket_allocation' => 100,
                'max_tickets_per_customer' => 5
            ]),
            new Event([
                'event_name' => 'Event 2',
                'start_datetime' => '2024-06-10',
                'end_datetime' => '2024-06-11',
                'city' => 'Los Angeles',
                'country' => 'USA',
                'ticket_allocation' => 100,
                'max_tickets_per_customer' => 5
            ]),
            new Event([
                'event_name' => 'Event 3',
                'start_datetime' => '2024-06-20',
                'end_datetime' => '2024-06-21',
                'city' => 'Chicago',
                'country' => 'Canada',
                'ticket_allocation' => 100,
                'max_tickets_per_customer' => 5
            ]),
            new Event([
                'event_name' => 'Event 4',
                'start_datetime' => '2024-05-01',
                'end_datetime' => '2024-05-02',
                'city' => 'Houston',
                'country' => 'Mexico',
                'ticket_allocation' => 100,
                'max_tickets_per_customer' => 5
            ]),
            new Event([
                'event_name' => 'Event 5',
                'start_datetime' => '2024-07-01',
                'end_datetime' => '2024-07-02',
                'city' => 'Phoenix',
                'country' => 'Brazil',
                'ticket_allocation' => 100,
                'max_tickets_per_customer' => 5
            ]),
        ]);
        $filteredEvents = collect([
            new Event([
                'event_name' => 'Event 1',
                'start_datetime' => '2024-06-05',
                'end_datetime' => '2024-06-06',
                'city' => 'New York',
                'country' => 'USA',
                'ticket_allocation' => 100,
                'max_tickets_per_customer' => 5
            ]),
            new Event([
                'event_name' => 'Event 2',
                'start_datetime' => '2024-06-10',
                'end_datetime' => '2024-06-11',
                'city' => 'Los Angeles',
                'country' => 'USA',
                'ticket_allocation' => 100,
                'max_tickets_per_customer' => 5
            ]),
            new Event([
                'event_name' => 'Event 3',
                'start_datetime' => '2024-06-20',
                'end_datetime' => '2024-06-21',
                'city' => 'Chicago',
                'country' => 'Canada',
                'ticket_allocation' => 100,
                'max_tickets_per_customer' => 5
            ]),
        ]);
        // Create a request with search parameters
        $request = new Request([
            'searchByDate' => '2024-06-01 to 2024-06-30',
        ]);
        
        $this->eventServiceMock
            ->shouldReceive('getEvents')
            ->once()
            ->with([
                'searchByDate' => '2024-06-01 to 2024-06-30',
            ])
            ->andReturn($filteredEvents);
        

        // Create an instance of the controller 
        $controller = new EventController($this->eventServiceMock);

        
        // Call the index method and get the response
        $response = $controller->index($request);
        
        $responsenew = $response->toResponse($request);

        // Assert that the response status is 200
        $this->assertEquals($responsenew->getStatusCode() , 200);

        $responseData =  $responsenew->original;
        $page = $responseData['page'];

        $this->assertEquals('Event/Index', $page['component']);
        
        //response has 3 events based on date search
        $this->assertCount(3, $page['props']['events']['data']);
        $this->assertEquals(EventResource::collection($filteredEvents)->response()->getData(true), 
                             $page['props']['events']);

    }
    public function test_filter_events_on_date_range_and_country()
    {
        $events = collect([
            new Event([
                'event_name' => 'Event 1',
                'start_datetime' => '2024-06-05',
                'end_datetime' => '2024-06-06',
                'city' => 'New York',
                'country' => 'USA',
                'ticket_allocation' => 100,
                'max_tickets_per_customer' => 5
            ]),
            new Event([
                'event_name' => 'Event 2',
                'start_datetime' => '2024-06-10',
                'end_datetime' => '2024-06-11',
                'city' => 'Los Angeles',
                'country' => 'USA',
                'ticket_allocation' => 100,
                'max_tickets_per_customer' => 5
            ]),
            new Event([
                'event_name' => 'Event 3',
                'start_datetime' => '2024-06-20',
                'end_datetime' => '2024-06-21',
                'city' => 'Chicago',
                'country' => 'Canada',
                'ticket_allocation' => 100,
                'max_tickets_per_customer' => 5
            ]),
            new Event([
                'event_name' => 'Event 4',
                'start_datetime' => '2024-05-01',
                'end_datetime' => '2024-05-02',
                'city' => 'Houston',
                'country' => 'Mexico',
                'ticket_allocation' => 100,
                'max_tickets_per_customer' => 5
            ]),
            new Event([
                'event_name' => 'Event 5',
                'start_datetime' => '2024-07-01',
                'end_datetime' => '2024-07-02',
                'city' => 'Phoenix',
                'country' => 'Brazil',
                'ticket_allocation' => 100,
                'max_tickets_per_customer' => 5
            ]),
        ]);
        $filteredEvents = collect([
            new Event([
                'event_name' => 'Event 1',
                'start_datetime' => '2024-06-05',
                'end_datetime' => '2024-06-06',
                'city' => 'New York',
                'country' => 'USA',
                'ticket_allocation' => 100,
                'max_tickets_per_customer' => 5
            ]),
            new Event([
                'event_name' => 'Event 2',
                'start_datetime' => '2024-06-10',
                'end_datetime' => '2024-06-11',
                'city' => 'Los Angeles',
                'country' => 'USA',
                'ticket_allocation' => 100,
                'max_tickets_per_customer' => 5
            ]),
        ]);
        // Create a request with search parameters
        $request = new Request([
            'searchByDate' => '2024-06-01 to 2024-06-30',
            'searchByCountry' => 'USA'
        ]);
        
        $this->eventServiceMock
            ->shouldReceive('getEvents')
            ->once()
            ->with([
                'searchByDate' => '2024-06-01 to 2024-06-30',
                'searchByCountry' => 'USA'
            ])
            ->andReturn($filteredEvents);
        

        // Create an instance of the controller 
        $controller = new EventController($this->eventServiceMock);

        
        // Call the index method and get the response
        $response = $controller->index($request);
        
        $responsenew = $response->toResponse($request);

        // Assert that the response status is 200
        $this->assertEquals($responsenew->getStatusCode() , 200);

        $responseData =  $responsenew->original;
        $page = $responseData['page'];

        $this->assertEquals('Event/Index', $page['component']);

        //assert that response has 2 events
        $this->assertCount(2, $page['props']['events']['data']);
        $this->assertEquals(EventResource::collection($filteredEvents)->response()->getData(true), 
                             $page['props']['events']);

    }
    public function test_book_event_successfully()
{
    $request = new BookEventRequest([
        'email' => 'test@example.com',
        'first_name' => 'FirstName',
        'last_name' => 'LastName',
        'event_id' => 1,
        'number_of_tickets' => 5,
    ]);

    $incomingData = [
        'email' => 'test@example.com',
        'first_name' => 'FirstName',
        'last_name' => 'LastName',
        'event_id' => 1,
        'number_of_tickets' => 5,
    ];
    $this->eventServiceMock
        ->shouldReceive('bookEvents')
        ->once()
        ->with($incomingData)
        ->andReturn(true);

    // Create an instance of the controller
    $controller = new EventController($this->eventServiceMock);

    // Call the book method and get the response
    $response = $controller->book($request);

    // Assert that the response is a redirect to the events index route
    $this->assertEquals(302, $response->getStatusCode());
    $this->assertEquals(route('events.index'), $response->headers->get('Location'));

    // Assert that the session has a success message
    $this->assertEquals('Your booking is successful.', session('success'));
}

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    
}
