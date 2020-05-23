<?php

declare(strict_types=1);

namespace App\Tests\Functional\Users;

use App\Tests\Functional\AuthFixture;
use App\Tests\Functional\DbWebTestCase;

class IndexTest extends DbWebTestCase
{
    public function testGuest(): void
    {
//        $client = static::createClient();
//        $client->request('GET', '/users');
        $this->client->request('GET', '/users');

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
        $this->assertSame('http://localhost/login', $this->client->getResponse()->headers->get('Location'));
    }

    public function testUser(): void
    {
//        $client = static::createClient([], AuthFixture::userCredentials());
//        $client->request('GET', '/users');
        $this->client->setServerParameters(AuthFixture::userCredentials());
        $this->client->request('GET', '/users');

        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testAdmin(): void
    {
//        $client = static::createClient([], AuthFixture::adminCredentials());
//        $crawler = $client->request('GET', '/users');
        $this->client->setServerParameters(AuthFixture::adminCredentials());
        $crawler = $this->client->request('GET', '/users');

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Users', $crawler->filter('title')->text());
    }
}