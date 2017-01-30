<?php

namespace AppBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
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
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/');

        $this->assertStatusCode(200, $client);
        $this->assertEquals('Cześć :)', $crawler->filter('.jumbotron .container h1')->text());
        $this->assertEquals(0, $crawler->filter('.container .row h1.col-xs-12')->count());
    }

    public function testJumbotronButtonClick()
    {
        $buttonSelector = '.jumbotron .container p .btn.btn-success';
        $brandLinkSelector = '.navbar .container .navbar-header .navbar-brand';
        $codesTableSelector = '.container .table-responsive .table';

        /*
         * Load homepage
         */
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/');

        $this->assertStatusCode(200, $client);

        /*
         * Click button in .jumbotron element to load list of codes
         */
        $button = $crawler->filter($buttonSelector)->link();
        /* @var $crawler Crawler */
        $crawler = $client->click($button);

        $this->assertStatusCode(200, $client);
        $this->assertEquals(1, $crawler->filter($codesTableSelector)->count());

        /*
         * Click brand / logo link to go to homepage
         */
        $brandLink = $crawler->filter($brandLinkSelector)->link();
        $crawler = $client->click($brandLink);

        $this->assertStatusCode(200, $client);
        $this->assertEquals(1, $crawler->filter($buttonSelector)->count());
    }
}
