<?php
namespace App\Repositories;

use App\Models\Event;

class EventRepository
{


    public function __construct()
    {

    }
    public function getEvents($incomingFields)
    {
        $query = Event::query();

        //$query = $this->applyfilters($query, $incomingFields);
        if(isset($incomingFields['searchByDate'])){
            $query = $this->applyDateFilter($query, $incomingFields['searchByDate']);
        }
        if(isset($incomingFields['searchByCountry'])){
            $query = $this->applyCountryFilter($query,  $incomingFields['searchByCountry']);
        }
        $query = $query->where('ticket_allocation', '>', 0);
        $query = $query->orderBy('start_datetime', 'asc'); 

        return $query->get();
    }
    public function getEventById($id)
    {

        $event = Event::findOrFail($id);
        return $event;
    }

    protected function applyDateFilter($query, $date)
    {
        if (strpos($date, ' to ') !== false) {
            $dates = explode(' to ', $date);
            $startDate = $dates[0];
            $endDate = $dates[1];
            return $query->whereBetween('start_datetime', [$startDate, $endDate]);
        } else {
            return $query->where('start_datetime', '>=', $date);
        }
    }

    protected function applyCountryFilter($query, $country)
    {
        return $query->where('country', 'like', '%' . $country . '%');
    }

    // protected function applyIdFilter($query, $id)
    // {
    //     $id = (int) $id;
    //     return $query->where('id', $id);
    // }

    public function updateEventTickets($incomingFields){
        $event = Event::findOrFail($incomingFields['event_id']);
        $event->decrement('ticket_allocation',  $incomingFields['number_of_tickets']);
        return $event;
    }
}
