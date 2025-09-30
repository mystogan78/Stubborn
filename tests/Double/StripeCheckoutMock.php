<?php

// tests/Double/StripeCheckoutMock.php
namespace App\Tests\Double;

use Stripe\Checkout\Session;

final class StripeCheckoutMock
{
    public function createSession(array $payload): Session
    {
        $s = new Session();
        $s->id = 'cs_test_12345';
        return $s;
    }
}

