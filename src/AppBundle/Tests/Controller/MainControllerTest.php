<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testHomepage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $statusCode = $client->getResponse()->getStatusCode();

        $this->assertEquals(200, $statusCode);
        $this->assertEquals('Cześć :)', $crawler->filter('.jumbotron .container h1')->text());
    }
}
