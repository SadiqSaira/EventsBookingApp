<?php

namespace App\Http\Requests\Event;
use App\Models\Event;

use Illuminate\Foundation\Http\FormRequest;

class BookEventRequest extends FormRequest
{
    protected Event $event;
    protected $allocatedTickets;
    protected $customerMaxTickets;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        /* Verify if the total number of available tickets has changed
        * while the user was on the booking screen due to other bookings.
        */
        
        $this->event = Event::findOrFail($this->input("event_id"));
        $this->allocatedTickets = $this->event->ticket_allocation;
        $this->customerMaxTickets = $this->input('max_number_of_tickets');
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        
       /* Select the minimum value between the total number of tickets left 
       * and the maximum number of tickets allowed per customer.
       */

        $maxTicketsAllowed = min($this->allocatedTickets, $this->customerMaxTickets);

        return [
            'event_id' => 'required|exists:events,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'number_of_tickets' => ['required','integer','min:1','max:'.$maxTicketsAllowed,],
        ];
    }
    /**
     * Custom messages for validation errors.
     *
     * @return array
     */
    public function messages()
    {
        $requestedTickets = $this->input('number_of_tickets');

        if ($requestedTickets > $this->customerMaxTickets) {
            $ticketsMessage = 'The maximum number of tickets you can book is '.$this->customerMaxTickets.'.';
        } elseif ($requestedTickets > $this->allocatedTickets) {
            $ticketsMessage = 'Tickets left for this event are '.$this->allocatedTickets.'.';
        } else {
            $ticketsMessage = 'Unknown validation error for number of tickets.';
        }
        return [
            'event_id.required' => 'The event ID is required.',
            'event_id.exists' => 'The selected event does not exist.',
            'first_name.required' => 'The first name is required.',
            'last_name.required' => 'The last name is required.',
            'first_name.string' => 'The first name must be a string.',
            'last_name.string' => 'The last name must be a string..',
            'email.required' => 'The email is required.',
            'email.email' => 'The email must be a valid email address.',
            'number_of_tickets.required' => 'The number of tickets is required.',
            'number_of_tickets.integer' => 'The number of tickets must be an integer.',
            'number_of_tickets.min' => 'You must book at least one ticket.',
            'number_of_tickets.max' => 'You cannot book more than :max_number_of_tickets tickets'.$ticketsMessage, 
        ];
    }
}
