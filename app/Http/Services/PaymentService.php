<?php

namespace App\Http\Services;

use App\Models\Order;

class PaymentService
{
    public function __construct(protected SubscriptionService $service, Order $order){}


}