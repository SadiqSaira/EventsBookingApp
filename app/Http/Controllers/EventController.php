<?php
namespace App\Http\Controllers;
use Illuminate\Http\RedirectResponse;

use Illuminate\Http\Request;
use App\Http\Requests\Event\BookEventRequest;
use App\Http\Resources\EventResource;
use App\Services\EventService;
use App\Models\Event;
use App\Models\Customer;
use App\Models\Booking;
//event controller
class EventController extends Controller
{
    protected $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function index(Request $request)
    {
        $events = $this->eventService->getEvents($request);
        return inertia('Event/Index', ['events' => EventResource::collection($events)]);
    }
    public function show(Request $request)
    {
        $events = $this->eventService->getEvents($request);
        return inertia('Event/BookEvent', ['events' => EventResource::collection($events),
                                            'eventId'=> $request['eventId']
                                          ]);
    }
    public function book(BookEventRequest $request)
    {
/*
        $event = Event::findOrFail($request->event_id);
        $allocatedTickets = $event->ticket_allocation;
        $request->validate([
            'number_of_tickets' => ['required','integer','min:1', 'max:'.$allocatedTickets],
        ]);

*/        
        // Create or update customer
        $customer = Customer::updateOrCreate(
            ['email' => $request->email],
            ['first_name' => $request->first_name, 'last_name' => $request->last_name]
        );
        
        // Use create method for booking
        $booking = Booking::create([
            'event_id' => $request->event_id,
            'customer_id' => $customer->id,
            'num_tickets_booked' => $request->number_of_tickets,
            'booking_datetime' => now(),
        ]);
        $event = Event::findOrFail($request->event_id);
        $event->decrement('ticket_allocation', $request->number_of_tickets);

        return redirect()->route('events.index')->with('success', 'Your booking is successful.');
    }
}
