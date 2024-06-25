<?php
namespace App\Repositories;


use Illuminate\Support\Facades\Log; 
use App\Models\Customer;

class CustomerRepository
{


    public function __construct()
    {

    }
    public function updateOrCreateByEmail($incomingFields)
    {
        // Create or update customer
        $customer = Customer::updateOrCreate(
            ['email' => $incomingFields['email']],
            ['first_name' => $incomingFields['first_name'], 'last_name' => $incomingFields['last_name']]
        );
        return $customer;
    }
}
