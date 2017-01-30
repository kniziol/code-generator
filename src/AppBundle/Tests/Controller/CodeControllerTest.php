<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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
        $client = static::createClient();

        $client->request('GET', '/codes/999');
        $statusCode = $client->getResponse()->getStatusCode();
        $this->assertEquals(404, $statusCode);

        $client->request('GET', '/codes/0');
        $statusCode = $client->getResponse()->getStatusCode();
        $this->assertEquals(404, $statusCode);

        $client->request('GET', '/codes/lorem');
        $statusCode = $client->getResponse()->getStatusCode();
        $this->assertEquals(404, $statusCode);
    }

    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/codes');
        $statusCode = $client->getResponse()->getStatusCode();

        $this->assertEquals(200, $statusCode);

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
        $client = static::createClient();
        $tableBodySelector = '.table-responsive .table tbody';
        $pagerSelector = '.pager li';

        /*
         * 1st page
         */
        $crawler = $client->request('GET', '/codes/1');
        $statusCode = $client->getResponse()->getStatusCode();

        $this->assertEquals(200, $statusCode);
        $this->assertEquals(10, $crawler->filter($tableBodySelector)->children()->count());

        $this->assertEquals(1, $crawler->filter($pagerSelector)->count());
        $this->assertContains('Następne', $crawler->filter(sprintf('%s a', $pagerSelector))->text());

        /*
         * 2nd page
         */
        $crawler = $client->request('GET', '/codes/2');
        $statusCode = $client->getResponse()->getStatusCode();

        $this->assertEquals(200, $statusCode);
        $this->assertEquals(5, $crawler->filter($tableBodySelector)->children()->count());

        $this->assertEquals(1, $crawler->filter($pagerSelector)->count());
        $this->assertContains('Poprzednie', $crawler->filter(sprintf('%s a', $pagerSelector))->text());

        /*
         * 3rd page - 404
         */
        $client->request('GET', '/codes/3');
        $statusCode = $client->getResponse()->getStatusCode();
        $this->assertEquals(404, $statusCode);
    }

    public function testIndexPaginationClick()
    {
        $client = static::createClient();
        $tableBodySelector = '.table-responsive .table tbody';
        $pagerSelector = '.pager li';

        /*
         * Load 1st page
         */
        $crawler = $client->request('GET', '/codes');
        $statusCode = $client->getResponse()->getStatusCode();

        $this->assertEquals(10, $crawler->filter($tableBodySelector)->children()->count());
        $this->assertEquals(200, $statusCode);

        /*
         * Click "next" button in pager
         */
        $nextLink = $crawler->filter(sprintf('%s:first-of-type a', $pagerSelector))->link();
        $crawler = $client->click($nextLink);
        $statusCode = $client->getResponse()->getStatusCode();

        $this->assertEquals(200, $statusCode);
        $this->assertEquals(5, $crawler->filter($tableBodySelector)->children()->count());

        /*
         * Click "previous" button in pager
         */
        $nextLink = $crawler->filter(sprintf('%s:first-of-type a', $pagerSelector))->link();
        $client->click($nextLink);

        $statusCode = $client->getResponse()->getStatusCode();
        $this->assertEquals(200, $statusCode);
    }
}
