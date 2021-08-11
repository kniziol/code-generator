<?php

declare(strict_types=1);

namespace App\Tests\Controller;

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

    public function testJumbotronShowCodesButton()
    {
        $buttonSelector = $this->getJumbotronButtonSelector('.btn-success');
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

    public function testJumbotronAddRandomCodesButton()
    {
        $buttonSelector = '.jumbotron .container .item .btn-group.add-codes .dropdown-menu .add-random a';
        $brandLinkSelector = '.navbar .container .navbar-header .navbar-brand';
        $alertSelector = '.container .row .col-xs-12 .alert.alert-success';

        /*
         * Load homepage
         */
        $client = $this->makeClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/');

        $this->assertStatusCode(200, $client);

        /*
         * Click button in .jumbotron element to generate new codes
         */
        $button = $crawler->filter($buttonSelector)->link();
        $crawler = $client->click($button);

        $this->assertStatusCode(200, $client);
        $this->assertEquals(1, $crawler->filter($buttonSelector)->count());
        $this->assertEquals(1, $crawler->filter($brandLinkSelector)->count());
        $this->assertEquals(1, $crawler->filter($alertSelector)->count());
        $this->assertEquals('Dodano losowe kody', $crawler->filter($alertSelector)->text());
    }

    public function testJumbotronAddCodesButton()
    {
        $buttonSelector = '.jumbotron .container .item .btn-group.add-codes .dropdown-menu .add a';
        $brandLinkSelector = '.navbar .container .navbar-header .navbar-brand';

        /*
         * Load homepage
         */
        $client = $this->makeClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/');

        $this->assertStatusCode(200, $client);
        $this->assertEquals(1, $crawler->filter($brandLinkSelector)->count());

        /*
         * Click button in .jumbotron element to display form used to add code
         */
        $button = $crawler->filter($buttonSelector)->link();
        $crawler = $client->click($button);

        $submitButton = $crawler->selectButton('Dodaj');
        $form = $submitButton->form();

        $this->assertStatusCode(200, $client);
        $this->assertNotEmpty($submitButton);
        $this->assertNotEmpty($form);
        $this->assertEquals('POST', $form->getMethod());
        $this->assertRegExp('#^http://[\w-.]+/codes/add-code$#', $form->getUri());
    }

    public function testJumbotronDeleteCodesButton()
    {
        $buttonSelector = $this->getJumbotronButtonSelector('.btn-danger');
        $formSelector = '.container form[name="code_delete"]';

        /*
         * Load homepage
         */
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/');

        $this->assertStatusCode(200, $client);

        /*
         * Click button in .jumbotron element to delete codes
         */
        $button = $crawler->filter($buttonSelector)->link();
        $crawler = $client->click($button);

        $this->assertStatusCode(200, $client);
        $this->assertEquals('Usuń kody', $crawler->filter('.container .row h1.col-xs-12')->text());
        $this->assertEquals(1, $crawler->filter($formSelector)->count());
    }

    /**
     * Returns CSS selector of concrete button or all buttons placed in jumbotron
     *
     * @param string $concreteSelector Selector of concrete button
     * @return string
     */
    private function getJumbotronButtonSelector($concreteSelector = '')
    {
        return sprintf('.jumbotron .container .item .btn%s', $concreteSelector);
    }
}
