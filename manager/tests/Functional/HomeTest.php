<?php

namespace App\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;

class HomeTest extends DbWebTestCase
{
    public function testGuest(): void
    {
//        $client = static::createClient();
//        $client->request('GET', '/');
        $this->client->request('GET', '/');

        $this->assertResponseRedirects(
            'http://localhost/login',
            Response::HTTP_FOUND,
            sprintf('The %s secure URL redirects to the login form.', '/')
        );

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertSame('http://localhost/login', $this->client->getResponse()->headers->get('Location'));
    }

    public function testUser(): void
    {
//        $client = static::createClient([], [
//            'PHP_AUTH_USER' => 'auth-user@app.test',
//            'PHP_AUTH_PW' => 'password',
//        ]);
//        $client = static::createClient([], AuthFixture::userCredentials());
//        $crawler = $client->request('GET', '/');

        $this->client->setServerParameters(AuthFixture::adminCredentials());
        $crawler = $this->client->request('GET', '/');

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Home', $crawler->filter('title')->text());
    }

    public function testAdmin(): void
    {
//        $client = static::createClient([], [
//            'PHP_AUTH_USER' => 'auth-admin@app.test',
//            'PHP_AUTH_PW' => 'password',
//        ]);
//        $client = static::createClient([], AuthFixture::adminCredentials());
//        $crawler = $client->request('GET', '/');
        $this->client->setServerParameters(AuthFixture::adminCredentials());
        $crawler = $this->client->request('GET', '/');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Home', $crawler->filter('title')->text());
    }
}