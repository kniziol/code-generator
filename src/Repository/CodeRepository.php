<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Code;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Code|null find($id, $lockMode = null, $lockVersion = null)
 * @method Code|null findOneBy(array $criteria, array $orderBy = null)
 * @method Code[]    findAll()
 * @method Code[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Code::class);
    }

    /**
     * Returns values of all codes.
     * Only the generated, scalar values.
     *
     * @return array
     */
    public function getCodesValues(): array
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

    /**
     * Returns codes with given values
     *
     * @param array $codesValues Values of codes to return
     * @return array
     */
    public function getCodesByValues(array $codesValues): array
    {
        $queryBuilder = $this->createQueryBuilder('c');
        $inExpression = $queryBuilder->expr()->in('c.value', $codesValues);

        return $queryBuilder
            ->andWhere($inExpression)
            ->getQuery()
            ->getResult()
        ;
    }
}
