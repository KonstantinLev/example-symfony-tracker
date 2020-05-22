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

    public function testUser(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'auth-user@app.test',
            'PHP_AUTH_PW' => 'password',
        ]);
        $crawler = $client->request('GET', '/');

        $this->assertSame(302, $client->getResponse()->getStatusCode());
        //$this->assertContains('Home', $crawler->filter('title')->text());
    }

    public function testAdmin(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'auth-admin@app.test',
            'PHP_AUTH_PW' => 'password',
        ]);
        $crawler = $client->request('GET', '/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Home', $crawler->filter('title')->text());
    }
}