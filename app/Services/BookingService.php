<?php
namespace App\Services;

use App\Repositories\BookingRepository;
use App\Services\BookingServiceInterface;

class BookingService implements BookingServiceInterface
{
    protected $bookingRepository;
    public function __construct(BookingRepository $bookingRepository){
        $this->bookingRepository = $bookingRepository;
    }
    public function createBooking($incomingFields, $customerId)
    {
        $booking = $this->bookingRepository->createBooking($incomingFields, $customerId);
        return $booking;
    }
}
