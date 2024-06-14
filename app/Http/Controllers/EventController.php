<?php
namespace App\Http\Controllers;
use Illuminate\Http\RedirectResponse;

use Illuminate\Http\Request;
use App\Http\Requests\Event\BookEventRequest;
use App\Http\Resources\EventResource;
use App\Services\EventService;

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
        $this->eventService->bookEvents($request);

        return redirect()->route('events.index')->with('success', 'Your booking is successful.');
    }
}
