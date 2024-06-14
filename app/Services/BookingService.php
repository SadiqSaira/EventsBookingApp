<?php
namespace App\Services;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Booking;

class BookingService
{

    public function createBooking(Request $request, $customerId)
    {
        $booking = Booking::create([
            'event_id' => $request->event_id,
            'customer_id' => $customerId,
            'num_tickets_booked' => $request->number_of_tickets,
            'booking_datetime' => now(),
        ]);
        return $booking;
    }
}
