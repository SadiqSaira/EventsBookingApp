<?php
namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EventFilter
{
    public function apply(Builder $query, Request $request)
    {
        // Apply search filters based on request parameters
        $query = $query->when($request->has('searchByDate'), function ($query) use ($request) {
            return $this->applyDateFilter($query, $request->input('searchByDate'));

        })->when($request->has('searchByCountry'), function ($query) use ($request) {
            return $this->applyCountryFilter($query, $request->input('searchByCountry'));

        })->when($request->has('eventId'), function ($query) use ($request) {
            return $this->applyIdFilter($query, $request->input('eventId'));
        });
         return $query->where('ticket_allocation', '>', 0);
    }

    protected function applyDateFilter(Builder $query, $date)
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

    protected function applyCountryFilter(Builder $query, $country)
    {
        return $query->where('country', 'like', '%' . $country . '%');
    }

    protected function applyIdFilter(Builder $query, $id)
    {
        $id = (int) $id;
        return $query->where('id', $id);
    }
}
