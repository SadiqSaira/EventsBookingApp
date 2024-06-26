<?php
namespace App\Services;

use App\Repositories\CustomerRepository;
use App\Services\CustomerServiceInterface;

class CustomerService implements CustomerServiceInterface
{
    protected $customerRepository;
    public function __construct(CustomerRepository $customerRepository){
        $this->customerRepository = $customerRepository;
    }
    public function updateOrCreateByEmail($incomingFields)
    {
        // Create or update customer
        $customer = $this->customerRepository->updateOrCreateByEmail($incomingFields);
        return $customer;
    }
}
