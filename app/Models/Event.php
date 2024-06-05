<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_name',
        'start_datetime',
        'end_datetime',
        'city',
        'country',
        'ticket_allocation',
        'max_tickets_per_customer',
    ];
}
