<?php

declare(strict_types=1);

namespace App\Helper;

/**
 * Helper for functionality related to pagination
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class PaginationHelper
{
    /**
     * Returns offset used for pagination.
     * It's value calculated using current page number and the "per page" amount.
     *
     * @param int $page Number of page
     * @return int
     */
    public function getPaginationOffsetValue(int $page)
    {
        if ($page <= 0) {
            $page = 1;
        }

        $limit = $this->getPaginationPerPageValue();

        return ($page - 1) * $limit;
    }

    /**
     * Returns amount of elements displayed on one page while using pagination
     * todo: Grab this value from application's configuration. Make it more flexible.
     *
     * @return int
     */
    public function getPaginationPerPageValue(): int
    {
        return 15;
    }

    /**
     * Returns count / amount of all pages
     *
     * @param int      $allItemsCount
     * @param int|null $perPage (optional)
     * @return int
     */
    public function getPagesCount(int $allItemsCount, int $perPage = null): int
    {
        if ($perPage === null) {
            $perPage = $this->getPaginationPerPageValue();
        }

        return (int)ceil($allItemsCount / $perPage);
    }

    /**
     * Returns next or previous number of page used for pagination.
     * If there is no next or previous page, returns null (example: for 1st page there is no previous).
     *
     * @param int      $current    Current number of page
     * @param int|null $pagesCount (optional) Count / amount of all pages
     * @param bool     $next       (optional) If is set to true, returns number of next page (default behaviour).
     *                             Otherwise - previous.
     * @return int|null
     */
    public function getPaginationPageNumber(int $current, int $pagesCount = null, bool $next = true): ?int
    {
        /*
         * Reject incorrect values:
         * - non-positive current number of page
         * OR
         * - current number of page greater than amount of all pages
         */
        if ($current < 1 || ($pagesCount !== null && $current > $pagesCount)) {
            return null;
        }

        /*
         * Let's look for number of next page
         */
        if ($next) {
            $nextPage = $current + 1;

            if ($pagesCount === null) {
                return $nextPage;
            }

            /*
             * Number of next page cannot be greater than amount of all pages
             */
            if ($nextPage > $pagesCount) {
                return null;
            }

            return $nextPage;
        }

        /*
         * Let's look for number of previous page
         */
        $previousPage = $current - 1;

        /*
         * Number of previous page cannot be non-positive value (lower than 1)
         */
        if ($previousPage < 1) {
            return null;
        }

        return $previousPage;
    }
}
