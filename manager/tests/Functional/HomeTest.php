<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomeTest extends WebTestCase
{
    public function testGuest(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseRedirects(
            'http://localhost/login',
            Response::HTTP_FOUND,
            sprintf('The %s secure URL redirects to the login form.', '/')
        );
    }
}