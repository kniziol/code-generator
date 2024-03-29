<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

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
        $this->assertEquals('Wszystkie kody', $crawler->filter('.container .row h1.col-xs-12')->text());

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
            $this->assertEquals(15, $crawler->filter($tableBodySelector)->children()->count());
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
        $this->assertEquals(15, $crawler->filter($tableBodySelector)->children()->count());

        $this->assertEquals(1, $crawler->filter($pagerSelector)->count());
        $this->assertStringContainsString('Następne', $crawler->filter(sprintf('%s a', $pagerSelector))->text());

        /*
         * 2nd page
         */
        $crawler = $client->request('GET', '/codes/2');

        $this->assertStatusCode(200, $client);
        $this->assertEquals(10, $crawler->filter($tableBodySelector)->children()->count());

        $this->assertEquals(1, $crawler->filter($pagerSelector)->count());
        $this->assertStringContainsString('Poprzednie', $crawler->filter(sprintf('%s a', $pagerSelector))->text());

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

        $this->assertEquals(15, $crawler->filter($tableBodySelector)->children()->count());
        $this->assertStatusCode(200, $client);

        /*
         * Click "next" button in pager
         */
        $nextLink = $crawler->filter(sprintf('%s:first-of-type a', $pagerSelector))->link();
        $crawler = $client->click($nextLink);

        $this->assertStatusCode(200, $client);
        $this->assertEquals(10, $crawler->filter($tableBodySelector)->children()->count());

        /*
         * Click "previous" button in pager
         */
        $nextLink = $crawler->filter(sprintf('%s:first-of-type a', $pagerSelector))->link();
        $client->click($nextLink);

        $this->assertStatusCode(200, $client);
    }

    public function testAddRandom()
    {
        $alertSelector = '.container .row .col-xs-12 .alert.alert-success';
        $client = $this->makeClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/codes/add-random-codes');

        $this->assertStatusCode(200, $client);
        $this->assertEquals('Dodano losowe kody', $crawler->filter($alertSelector)->text());
    }

    /**
     * @param string $code            The code to add
     * @param string $validationError Validation error text
     *
     * @dataProvider getCodes
     */
    public function testAddCode($code, $validationError)
    {
        $formGroupSelector = '.container form[name="code"] .form-group.has-error';
        $validationErrorSelector = sprintf('%s span.help-block li', $formGroupSelector);
        $alertSelector = '.container .row .col-xs-12 .alert.alert-success';

        $client = $this->makeClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/codes/add-code');

        $this->assertStatusCode(200, $client);

        $submitButton = $crawler->selectButton('Dodaj');
        $form = $submitButton->form();

        $crawler = $client->submit($form, [
            'code[value]' => $code,
        ]);

        $codeIsValid = empty($validationError);
        $formGroupCount = 1;
        $alertCount = 0;

        if ($codeIsValid) {
            $formGroupCount = 0;
            $alertCount = 1;
        }

        $this->assertStatusCode(200, $client);
        $this->assertEquals($formGroupCount, $crawler->filter($formGroupSelector)->count());
        $this->assertEquals($alertCount, $crawler->filter($alertSelector)->count());

        if (!$codeIsValid) {
            $this->assertStringContainsString($validationError, $crawler->filter($validationErrorSelector)->text());
        }
    }

    public function testDelete()
    {
        $formSelector = '.container form[name="code_delete"]';

        /*
         * Load the "delete codes" form
         */
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/codes/delete');

        $this->assertStatusCode(200, $client);
        $this->assertEquals(1, $crawler->filter($formSelector)->count());
    }

    public function testDeleteValidation()
    {
        $formGroupSelector = '.container form[name="code_delete"] .form-group.has-error';
        $validationErrorSelector = sprintf('%s span.help-block li', $formGroupSelector);
        $alertSelector = '.container .row .col-xs-12 .alert.alert-danger';

        /*
         * Load the "delete codes" form
         */
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/codes/delete');

        /*
         * Submit the form without any codes to delete
         */
        $form = $crawler->selectButton('Remove')->form();
        $crawler = $client->submit($form);

        $this->assertStatusCode(200, $client);
        $this->assertEquals(1, $crawler->filter($formGroupSelector)->count());
        $this->assertEquals(0, $crawler->filter($alertSelector)->count());
        $this->assertStringContainsString('Ta wartość nie powinna być pusta.', $crawler->filter($validationErrorSelector)
            ->text());

        /*
         * Submit the form without too short code
         */
        $form = $crawler->selectButton('Usuń')->form();
        $crawler = $client->submit($form, ['code_delete[codes_values]' => 123]);

        $this->assertStatusCode(200, $client);
        $this->assertEquals(1, $crawler->filter($formGroupSelector)->count());
        $this->assertEquals(0, $crawler->filter($alertSelector)->count());
        $this->assertStringContainsString('Ta wartość jest zbyt krótka. Powinna mieć 9 lub więcej znaków.',
            $crawler->filter($validationErrorSelector)->text());

        /*
         * Submit the form with non-existing code
         */
        $form = $crawler->selectButton('Usuń')->form();
        $crawler = $client->submit($form, ['code_delete[codes_values]' => 123456789]);

        $this->assertStatusCode(200, $client);
        $this->assertEquals(0, $crawler->filter($formGroupSelector)->count());
        $this->assertEquals(0, $crawler->filter($validationErrorSelector)->count());
        $this->assertEquals('Conajmniej 1 kod jest niepoprawny. Kody nie zostały usunięte.',
            $crawler->filter($alertSelector)->text());
    }

    /**
     * Provides invalid codes with validation errors
     *
     * @return array
     */
    public function getCodes()
    {
        yield [
            'code'             => '',
            'validation_error' => 'Ta wartość nie powinna być pusta.',
        ];

        yield [
            'code'             => 'ab',
            'validation_error' => 'Ta wartość jest zbyt krótka. Powinna mieć 3 lub więcej znaków.',
        ];

        yield [
            'code'             => 'testing something',
            'validation_error' => 'Ta wartość jest zbyt długa. Powinna mieć 10 lub mniej znaków.',
        ];

        yield [
            'code'             => '1234567890',
            'validation_error' => '',
        ];
    }
}
