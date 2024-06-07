<?php
namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EventFilter
{
    public function apply(Builder $query, Request $request)
    {
        //search filters based on request parameters
        return $query->when($request->has('searchByDate'), function ($query) use ($request) {
            // date filter
            return $this->applyDateFilter($query, $request->input('searchByDate'));
        })->when($request->has('searchByCountry'), function ($query) use ($request) {
            // country filter
            return $this->applyCountryFilter($query, $request->input('searchByCountry'));
        });
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
}
