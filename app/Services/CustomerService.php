<?php
namespace App\Services;

use App\Models\Customer;
use Illuminate\Http\Request;


class CustomerService
{
    protected $request;
    public function __construct(Request $request){
        $this->request = $request;
    }
    public function updateOrCreateByEmail()
    {
        // Create or update customer
        $customer = Customer::updateOrCreate(
            ['email' => $this->request->email],
            ['first_name' => $this->request->first_name, 'last_name' => $this->request->last_name]
        );
        return $customer;
    }
}
