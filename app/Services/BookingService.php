<?php
namespace App\Services;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Booking;

class BookingService
{
    protected $request;
    public function __construct(Request $request){
        $this->request = $request;
    }
    public function createBooking($customerId)
    {
        $booking = Booking::create([
            'event_id' => $this->request->event_id,
            'customer_id' => $customerId,
            'num_tickets_booked' => $this->request->number_of_tickets,
            'booking_datetime' => now(),
        ]);
        return $booking;
    }
}
