<?php

namespace App\Services;

interface EventServiceInterface
{
    public function getEvents(array $incomingFields);
    public function getEventById(int $id);
    public function bookEvents(array $incomingFields);
}
