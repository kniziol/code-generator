<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\Code;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validator used by constraint for unique value of code.
 * Verifies if provided value of code does not exists in database.
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class UniqueCodeValueValidator extends ConstraintValidator
{
    /**
     * The central access point to ORM functionality
     *
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Class constructor
     *
     * @param EntityManager $entityManager The central access point to ORM functionality
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        /*
         * Value of code not provided?
         * Nothing to do
         */
        if (empty($value)) {
            return;
        }

        /* @var Code $code */
        $code = $this
            ->entityManager
            ->getRepository(Code::class)
            ->findOneBy([
                'value' => $value,
            ]);

        /*
         * Code with given value doesn't exist?
         * Nothing to do
         */
        if ($code === null) {
            return;
        }

        $this
            ->context
            ->buildViolation($constraint->message)
            ->addViolation();
    }
}
