<?php

declare(strict_types=1);

namespace App\Helper;

class PaginationHelper
{
    public function getPaginationOffsetValue(int $page)
    {
        if ($page <= 0) {
            $page = 1;
        }

        $limit = $this->getPaginationPerPageValue();

        return ($page - 1) * $limit;
    }

    public function getPaginationPerPageValue(): int
    {
        // todo: Grab this value from application's configuration. Make it more flexible.
        return 15;
    }

    public function getPagesCount(int $allItemsCount, int $perPage = null): int
    {
        if ($perPage === null) {
            $perPage = $this->getPaginationPerPageValue();
        }

        return (int)ceil($allItemsCount / $perPage);
    }

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
