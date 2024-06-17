<?php
namespace App\Services;

use App\Models\Event;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Requests\Event\BookEventRequest;
use App\Services\CustomerService;

class EventService
{
    public function getEvents(Request $request)
    {
        $query = Event::query();
        $eventFilter = new EventFilter($query, $request);

        $query = $eventFilter->apply();

        return $query->get();
    }
    public function bookEvents(BookEventRequest $request)
    {
        // Create or update customer
        $customer = (new CustomerService())->updateOrCreateByEmail($request);

        //Create booking
        $booking = (new BookingService())->createBooking($request, $customer->id);

        //update Events Tickets 
        $event = $this->updateEventTickets($request);
    }

    public function updateEventTickets(Request $request){
        $event = Event::findOrFail($request->event_id);
        $event->decrement('ticket_allocation', $request->number_of_tickets);
        return $event;
    }
}
