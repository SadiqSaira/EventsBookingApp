<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\EventService;
use App\Services\CustomerServiceInterface;
use App\Services\BookingServiceInterface;
use App\Repositories\EventRepositoryInterface;
use Mockery;
use App\Models\Event;
use App\Models\Customer;
use App\Models\Booking;
class EventServiceTest extends TestCase
{
    protected $customerServiceMock;
    protected $bookingServiceMock;
    protected $eventRepositoryMock;
    protected $eventService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customerServiceMock = Mockery::mock(CustomerServiceInterface::class);
        $this->bookingServiceMock = Mockery::mock(BookingServiceInterface::class);
        $this->eventRepositoryMock = Mockery::mock(EventRepositoryInterface::class);

        $this->eventService = new EventService(
            $this->customerServiceMock,
            $this->bookingServiceMock,
            $this->eventRepositoryMock
        );
    }

    public function test_get_event_by_id()
    {
        $eventId = 1;
        $event = new Event([
            'event_name' => 'Event 1',
            'start_datetime' => '2024-06-05',
            'end_datetime' => '2024-06-06',
            'city' => 'New York',
            'country' => 'USA',
            'ticket_allocation' => 100,
            'max_tickets_per_customer' => 5
        ]);

        $this->eventRepositoryMock
            ->shouldReceive('getEventById')
            ->once()
            ->with($eventId)
            ->andReturn($event);

        $result = $this->eventService->getEventById($eventId);

        $this->assertEquals($event, $result);
    }

    public function test_get_events()
    {
        $incomingFields = [];
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
        ]);

        $this->eventRepositoryMock
            ->shouldReceive('getEvents')
            ->once()
            ->with($incomingFields)
            ->andReturn($events);

        $result = $this->eventService->getEvents($incomingFields);

        $this->assertCount(2, $result);
        $this->assertInstanceOf(Event::class, $result->first());
    }

    public function test_book_events()
    {
        $incomingFields = [
            'email' => 'test@example.com',
            'first_name' => 'FirstName',
            'last_name' => 'LastName',
            'event_id' => 1,
            'number_of_tickets' => 5,
        ];
        $event = new Event(['id' => 1,
                            'event_name' => 'Event 1',
                            'start_datetime' => '2024-06-10',
                            'end_datetime' => '2024-06-11',
                            'city' => 'Los Angeles',
                            'country' => 'USA',
                            'ticket_allocation' => 100,
                            'max_tickets_per_customer' => 5
                            ]);
        $customer = new Customer(['id' => 1, 
                                  'email' => 'test@example.com',
                                  'first_name' => 'FirstName',
                                  'last_name' => 'LastName',
                                  ]);
        $booking = new Booking(['event_id'=> 1,
                                'customer_id'=> 1,
                                'num_tickets_booked'=>5,
                                ]);

        $this->customerServiceMock
            ->shouldReceive('updateOrCreateByEmail')
            ->once()
            ->with($incomingFields)
            ->andReturn($customer);

        $this->bookingServiceMock
            ->shouldReceive('createBooking')
            ->once()
            ->with($incomingFields, $customer->id)
            ->andReturn($booking);

        $this->eventRepositoryMock
            ->shouldReceive('updateEventTickets')
            ->once()
            ->with($incomingFields)
            ->andReturn($event);

        $this->eventService->bookEvents($incomingFields);

        // If no exceptions are thrown, the test passes
        $this->assertTrue(true);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
