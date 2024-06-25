<?php
namespace App\Services;

use App\Models\Event;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Requests\Event\BookEventRequest;
use App\Services\CustomerService;
use App\Services\BookingService;
use App\Repositories\EventRepository;

class EventService
{
    protected CustomerService $CustomerService;
    protected BookingService $bookingService;
    protected EventRepository $eventRepository;

    public function __construct(CustomerService $CustomerService, 
                                BookingService $bookingService,
                                EventRepository $eventRepository)
    {
        $this->CustomerService = $CustomerService;
        $this->bookingService = $bookingService;
        $this->eventRepository = $eventRepository;
    }
    public function getEventById($id)
    {
        return $this->eventRepository->getEventById($id);

    }
    public function getEvents($incomingFields)
    {

       return $this->eventRepository->getEvents($incomingFields);

    }
    public function bookEvents($incomingFields)
    {
        // Create or update customer
        $customer = $this->CustomerService->updateOrCreateByEmail($incomingFields);

        //Create booking
        $booking = $this->bookingService->createBooking( $incomingFields, $customer->id );

        //update Events Tickets 
        $event = $this->eventRepository->updateEventTickets($incomingFields);
    }


}
