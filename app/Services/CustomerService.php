<?php
namespace App\Services;

use App\Models\Customer;
use App\Http\Requests\Event\BookEventRequest;


class CustomerService
{

    public function updateOrCreateByEmail(BookEventRequest $request)
    {
        // Create or update customer
        $customer = Customer::updateOrCreate(
            ['email' => $request->email],
            ['first_name' => $request->first_name, 'last_name' => $request->last_name]
        );
        return $customer;
    }
}
