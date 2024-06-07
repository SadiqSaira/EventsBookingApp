<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'event_name' => $this->event_name,
            'start_datetime' => $this->start_datetime,
            'end_datetime' => $this->end_datetime,
            'city' => $this->city,
            'country' => $this->country,
            'ticket_allocation' => $this->ticket_allocation,
            'max_tickets_per_customer' => $this->max_tickets_per_customer,
        ];
    }
}
