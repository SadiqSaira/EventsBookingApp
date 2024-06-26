<?php

namespace App\Services;

interface BookingServiceInterface
{
    public function createBooking($incomingFields, $customerId);
}
