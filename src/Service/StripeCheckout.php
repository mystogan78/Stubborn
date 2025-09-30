<?php

namespace App\Service;

use Stripe\Checkout\Session as CheckoutSession;
use Stripe\Stripe;

final class StripeCheckout
{
    public function __construct(private string $secretKey)
    {
        Stripe::setApiKey($this->secretKey);
    }

    public function createSession(array $payload): CheckoutSession
    {
        return CheckoutSession::create($payload);
    }
}
