<?php

namespace App\Repositories;

interface EventRepositoryInterface
{
    public function getEvents($incomingFields);
    public function getEventById($id);
    public function updateEventTickets($incomingFields);
    public function applyDateFilter($query, $date);
    public function applyCountryFilter($query, $country);
}
