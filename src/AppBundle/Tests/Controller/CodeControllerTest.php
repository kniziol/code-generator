<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CodeControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $statusCode = $client->getResponse()->getStatusCode();

        $this->assertEquals(200, $statusCode);
        $this->assertEquals('Cześć :)', $crawler->filter('.jumbotron .container h1')->text());
    }
}
