<?php

declare(strict_types=1);

namespace App\Tests\Helper;

use App\Helper\PaginationHelper;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PaginationHelperTest extends KernelTestCase
{
    /**
     * Helper for functionality related to pagination
     *
     * @var PaginationHelper
     */
    private static $paginationHelper;

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        self::$paginationHelper = $container->get(PaginationHelper::class);
    }

    public function testGetPaginationPerPageValue()
    {
        $this->assertGreaterThan(0, self::$paginationHelper->getPaginationPerPageValue());
    }

    public function testGetPagesCount()
    {
        /*
         * Negative cases
         */
        $this->assertEquals(0, self::$paginationHelper->getPagesCount(0));
        $this->assertEquals(0, self::$paginationHelper->getPagesCount(-1));

        /*
         * Positive cases
         */
        $this->assertEquals(1, self::$paginationHelper->getPagesCount(1));
        $this->assertEquals(1, self::$paginationHelper->getPagesCount(10));
        $this->assertEquals(2, self::$paginationHelper->getPagesCount(20));

        $this->assertEquals(1, self::$paginationHelper->getPagesCount(1, 5));
        $this->assertEquals(2, self::$paginationHelper->getPagesCount(10, 5));
        $this->assertEquals(3, self::$paginationHelper->getPagesCount(15, 6));
    }

    public function testGetPaginationOffsetValue()
    {
        /*
         * Negative cases
         */
        $this->assertEquals(0, self::$paginationHelper->getPaginationOffsetValue(0));
        $this->assertEquals(0, self::$paginationHelper->getPaginationOffsetValue(-1));

        /*
         * Positive cases
         */
        $this->assertEquals(0, self::$paginationHelper->getPaginationOffsetValue(1));
        $this->assertEquals(60, self::$paginationHelper->getPaginationOffsetValue(5));
        $this->assertEquals(135, self::$paginationHelper->getPaginationOffsetValue(10));
    }

    public function testGetPaginationPageNumber()
    {
        /*
         * Negative cases
         */
        $this->assertNull(self::$paginationHelper->getPaginationPageNumber(0));
        $this->assertNull(self::$paginationHelper->getPaginationPageNumber(-1));
        $this->assertNull(self::$paginationHelper->getPaginationPageNumber(1, 0));
        $this->assertNull(self::$paginationHelper->getPaginationPageNumber(1, 1));
        $this->assertNull(self::$paginationHelper->getPaginationPageNumber(5, 4));
        $this->assertNull(self::$paginationHelper->getPaginationPageNumber(1, 1, false));
        $this->assertNull(self::$paginationHelper->getPaginationPageNumber(1, 2, false));
        $this->assertNull(self::$paginationHelper->getPaginationPageNumber(5, 3, false));

        /*
         * Positive cases
         */
        $this->assertEquals(2, self::$paginationHelper->getPaginationPageNumber(1));
        $this->assertEquals(2, self::$paginationHelper->getPaginationPageNumber(1, 2));
        $this->assertEquals(5, self::$paginationHelper->getPaginationPageNumber(4, 5));
        $this->assertEquals(3, self::$paginationHelper->getPaginationPageNumber(4, 5, false));
        $this->assertEquals(4, self::$paginationHelper->getPaginationPageNumber(5, 5, false));
    }
}
