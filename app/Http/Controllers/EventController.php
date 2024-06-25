<?php
namespace App\Http\Controllers;
use Illuminate\Http\RedirectResponse;

use Illuminate\Http\Request;
use App\Http\Requests\Event\BookEventRequest;
use App\Http\Resources\EventResource;
use App\Services\EventService;
use Illuminate\Support\Facades\Log; 
use App\Models\Event;


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
        $incomingFields['id'] = strip_tags($request['eventId']);
        $event = $this->eventService->getEventById($incomingFields['id']);
        
        return inertia('Event/BookEvent', ['events' => new EventResource($event),
                                            'eventId'=> $request['eventId']
                                          ]);
    }
    public function book(BookEventRequest $request)
    {

        $incomingFields['email'] = strip_tags($request['email']);
        $incomingFields['first_name'] = strip_tags($request['first_name']);
        $incomingFields['last_name'] = strip_tags($request['last_name']);
        $incomingFields['event_id'] = strip_tags($request['event_id']);
        $incomingFields['number_of_tickets'] = strip_tags($request['number_of_tickets']);


        $this->eventService->bookEvents($incomingFields);

        return redirect()->route('events.index')->with('success', 'Your booking is successful.');
    }
}
