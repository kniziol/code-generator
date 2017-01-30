<?php

namespace AppBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Test cases for the code-related controller
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class CodeControllerTest extends WebTestCase
{
    public function testIndex404()
    {
        $client = $this->makeClient();

        $client->request('GET', '/codes/999');
        $this->assertStatusCode(404, $client);

        $client->request('GET', '/codes/0');
        $this->assertStatusCode(404, $client);

        $client->request('GET', '/codes/lorem');
        $this->assertStatusCode(404, $client);
    }

    public function testIndex()
    {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/codes');

        $this->assertStatusCode(200, $client);
        $this->assertEquals('Wygenerowane kody', $crawler->filter('.container .row h1.col-xs-12')->text());

        /*
         * Headers of table / list
         */
        $tableHeaderSelector = '.table-responsive .table thead';

        $this->assertEquals('#', $crawler->filter(sprintf('%s tr th:nth-child(1)', $tableHeaderSelector))->text());
        $this->assertEquals('Kod', $crawler->filter(sprintf('%s tr th:nth-child(2)', $tableHeaderSelector))->text());

        $createdColumnName = $crawler->filter(sprintf('%s tr th:nth-child(3)', $tableHeaderSelector))->text();
        $this->assertEquals('Utworzono', $createdColumnName);

        /*
         * Rows of table / list
         */
        $tableBodySelector = '.table-responsive .table tbody';

        if ($crawler->filter(sprintf('%s tr td h4', $tableBodySelector))->count() == 1) {
            $this->assertEquals(1, $crawler->filter($tableBodySelector)->children()->count());

            $noDataText = $crawler->filter(sprintf('%s tr td h4', $tableBodySelector))->text();
            $this->assertEquals('Niestety nie ma kodów', $noDataText);
        } else {
            /*
             * One / 1st page with codes
             */
            $this->assertEquals(10, $crawler->filter($tableBodySelector)->children()->count());
        }
    }

    public function testIndexPagination()
    {
        $client = $this->makeClient();
        $tableBodySelector = '.table-responsive .table tbody';
        $pagerSelector = '.pager li';

        /*
         * 1st page
         */
        $crawler = $client->request('GET', '/codes/1');

        $this->assertStatusCode(200, $client);
        $this->assertEquals(10, $crawler->filter($tableBodySelector)->children()->count());

        $this->assertEquals(1, $crawler->filter($pagerSelector)->count());
        $this->assertContains('Następne', $crawler->filter(sprintf('%s a', $pagerSelector))->text());

        /*
         * 2nd page
         */
        $crawler = $client->request('GET', '/codes/2');

        $this->assertStatusCode(200, $client);
        $this->assertEquals(5, $crawler->filter($tableBodySelector)->children()->count());

        $this->assertEquals(1, $crawler->filter($pagerSelector)->count());
        $this->assertContains('Poprzednie', $crawler->filter(sprintf('%s a', $pagerSelector))->text());

        /*
         * 3rd page - 404
         */
        $client->request('GET', '/codes/3');
        $this->assertStatusCode(404, $client);
    }

    public function testIndexPaginationClick()
    {
        $client = $this->makeClient();
        $tableBodySelector = '.table-responsive .table tbody';
        $pagerSelector = '.pager li';

        /*
         * Load 1st page
         */
        $crawler = $client->request('GET', '/codes');

        $this->assertEquals(10, $crawler->filter($tableBodySelector)->children()->count());
        $this->assertStatusCode(200, $client);

        /*
         * Click "next" button in pager
         */
        $nextLink = $crawler->filter(sprintf('%s:first-of-type a', $pagerSelector))->link();
        $crawler = $client->click($nextLink);

        $this->assertStatusCode(200, $client);
        $this->assertEquals(5, $crawler->filter($tableBodySelector)->children()->count());

        /*
         * Click "previous" button in pager
         */
        $nextLink = $crawler->filter(sprintf('%s:first-of-type a', $pagerSelector))->link();
        $client->click($nextLink);

        $this->assertStatusCode(200, $client);
    }
}
