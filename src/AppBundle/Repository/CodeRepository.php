<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Repository for the Code entity
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class CodeRepository extends EntityRepository
{
    /**
     * Returns values of all codes.
     * Only the generated, scalar values.
     *
     * @return array
     */
    public function getCodesValues()
    {
        $result = $this
            ->createQueryBuilder('c')
            ->select('c.value')
            ->getQuery()
            ->getScalarResult();

        if (empty($result)) {
            return [];
        }

        $codes = [];

        foreach ($result as $row) {
            $codes[] = $row['value'];
        }

        return $codes;
    }
}
