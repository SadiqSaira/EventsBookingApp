<?php
namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EventFilter
{
    protected $query;
    protected $request;

    public function __construct(Builder $query, Request $request)
    {
        $this->query = $query;
        $this->request = $request;
    }

    public function apply()
    {
        // Apply search filters based on request parameters
        $this->query = $this->query->when($this->request->has('searchByDate'), function ($query) {
            return $this->applyDateFilter($this->request->input('searchByDate'));

        })->when($this->request->has('searchByCountry'), function ($query) {
            return $this->applyCountryFilter($this->request->input('searchByCountry'));

        })->when($this->request->has('eventId'), function ($query){
            return $this->applyIdFilter($this->request->input('eventId'));
        });
         return $this->query->where('ticket_allocation', '>', 0);
    }

    protected function applyDateFilter($date)
    {
        if (strpos($date, ' to ') !== false) {
            $dates = explode(' to ', $date);
            $startDate = $dates[0];
            $endDate = $dates[1];
            return $this->query->whereBetween('start_datetime', [$startDate, $endDate]);
        } else {
            return $this->query->where('start_datetime', '>=', $date);
        }
    }

    protected function applyCountryFilter($country)
    {
        return $this->query->where('country', 'like', '%' . $country . '%');
    }

    protected function applyIdFilter($id)
    {
        $id = (int) $id;
        return $this->query->where('id', $id);
    }
}
