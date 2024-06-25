<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 

class EventRepository
{


    public function __construct()
    {

    }

    public function apply($query, $request)
    {
        // Apply search filters based on request parameters
        $query = $query->when($request->has('searchByDate'), function ($q) use ($request){
            return $this->applyDateFilter($q, $request->input('searchByDate'));

        })->when($request->has('searchByCountry'), function ($q) use ($request) {
            return $this->applyCountryFilter($q, $request->input('searchByCountry'));

        });
         $filteredQuery = $query->where('ticket_allocation', '>', 0);

         return $filteredQuery;
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

    protected function applyIdFilter($query, $id)
    {
        $id = (int) $id;
        return $query->where('id', $id);
    }
}
