<?php
namespace App\Services;

use App\Models\Customer;
use App\Models\Booking;

class BookingService
{
    public function __construct(){
    }
    public function createBooking($incomingFields, $customerId)
    {
        $booking = Booking::create([
            'event_id' => $incomingFields['event_id'],
            'customer_id' => $customerId,
            'num_tickets_booked' => $incomingFields['number_of_tickets'],
            'booking_datetime' => now(),
        ]);
        return $booking;
    }
}
