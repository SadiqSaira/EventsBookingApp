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
    protected $request;
    public function __construct(EventService $eventService, Request $request)
    {
        $this->eventService = $eventService;
        $this->request = $request;
    }

    public function index()
    {
        $events = $this->eventService->getEvents();
        return inertia('Event/Index', ['events' => EventResource::collection($events)]);
    }
    public function show()
    {
        //$events = $this->eventService->getEvents($request);
        $event = $this->eventService->getEventById();
        
        return inertia('Event/BookEvent', ['events' => new EventResource($event),
                                            'eventId'=> $this->request['eventId']
                                          ]);
    }
    public function book()
    {
        $this->eventService->bookEvents();

        return redirect()->route('events.index')->with('success', 'Your booking is successful.');
    }
}
