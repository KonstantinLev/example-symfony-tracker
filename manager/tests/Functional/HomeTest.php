<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeTest extends WebTestCase
{
    public function testGuest(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertSame(302, $client->getResponse()->getStatusCode());
        $this->assertSame('http://localhost/login', $client->getResponse()->headers->get('Location'));
    }
    public function testSuccess(): void
    {
//        $client = static::createClient([], [
//            'PHP_AUTH_USER' => 'admin@app.test',
//            'PHP_AUTH_PW' => 'password',
//        ]);
        $client = static::createClient([
            'username' => 'admin@app.test',
            'password' => 'password',
        ]);
        $client = static::makeClient(true);
//        $client->disableReboot();
//        $client->setServerParameters([
//            'PHP_AUTH_USER' => 'admin@app.test',
//            'PHP_AUTH_PW' => 'password',
//        ]);
        $crawler = $client->request('GET', '/');
        //$this->assertSame(200, $client->getResponse()->getStatusCode());
        var_dump($client->getResponse()->headers->get('Location'));
        $this->assertContains('Hello World!', $crawler->filter('h1')->text());
    }
}