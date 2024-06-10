<?php
namespace App\Services;

use App\Models\Event;
use Illuminate\Http\Request;

class EventService
{
    public function getEvents(Request $request)
    {
        $query = Event::query();

        $query = (new EventFilter())->apply($query, $request);

        return $query->get();
    }
    
}
