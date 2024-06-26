<?php

namespace App\Services;

interface CustomerServiceInterface
{
    public function updateOrCreateByEmail(array $incomingFields);
}
