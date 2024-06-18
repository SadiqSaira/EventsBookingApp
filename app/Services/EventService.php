<?php
namespace App\Services;

use App\Models\Event;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Requests\Event\BookEventRequest;
use App\Services\CustomerService;

class EventService
{
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function getEventById()
    {
        $event = Event::findOrFail($this->request['eventId']);
        return $event;
    }
    public function getEvents()
    {
        $query = Event::query();
        $eventFilter = new EventFilter($query, $this->request);

        $query = $eventFilter->apply();

        return $query->get();
    }
    public function bookEvents()
    {
        // Create or update customer
        $customer = (new CustomerService($this->request))->updateOrCreateByEmail();

        //Create booking
        $booking = (new BookingService($this->request))->createBooking( $customer->id);

        //update Events Tickets 
        $event = $this->updateEventTickets();
    }

    public function updateEventTickets(){
        $event = Event::findOrFail($this->request->event_id);
        $event->decrement('ticket_allocation', $this->request->number_of_tickets);
        return $event;
    }
}
