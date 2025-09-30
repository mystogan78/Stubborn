<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Service\StripeCheckout;
use App\Tests\Double\StripeCheckoutMock;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CheckoutControllerTest extends WebTestCase
{
    public function test_sanity(): void
    {
        // 1. Créer un client => ça boot le kernel automatiquement
        $client = static::createClient();

        // 2. On peut accéder au container APRÈS createClient()
        static::getContainer()->set(StripeCheckout::class, new StripeCheckoutMock());

        // 3. Faire une requête de test
        $client->request('GET', '/checkout/cancel');

        // 4. Vérifier que la page répond
        $this->assertResponseIsSuccessful();
    }
}

