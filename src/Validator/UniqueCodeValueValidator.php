<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\Code;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class UniqueCodeValueValidator extends ConstraintValidator
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint): void
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
            ->addViolation()
        ;
    }
}
