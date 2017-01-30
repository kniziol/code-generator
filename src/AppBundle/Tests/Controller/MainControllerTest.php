<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Test cases for the main controller
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
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

    public function testJumbotronButtonClick()
    {
        $buttonSelector = '.jumbotron .container p .btn.btn-success';
        $brandLinkSelector = '.navbar .container .navbar-header .navbar-brand';
        $codesTableSelector = '.container .table-responsive .table';

        /*
         * Load homepage
         */
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $statusCode = $client->getResponse()->getStatusCode();

        $this->assertEquals(200, $statusCode);

        /*
         * Click button in .jumbotron element to load list of codes
         */
        $button = $crawler->filter($buttonSelector)->link();
        /* @var $crawler Crawler */
        $crawler = $client->click($button);
        $statusCode = $client->getResponse()->getStatusCode();

        $this->assertEquals(200, $statusCode);
        $this->assertEquals(1, $crawler->filter($codesTableSelector)->count());

        /*
         * Click brand / logo link to go to homepage
         */
        $brandLink = $crawler->filter($brandLinkSelector)->link();
        $crawler = $client->click($brandLink);
        $statusCode = $client->getResponse()->getStatusCode();

        $this->assertEquals(200, $statusCode);
        $this->assertEquals(1, $crawler->filter($buttonSelector)->count());
    }
}
