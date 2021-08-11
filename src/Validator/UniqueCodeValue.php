<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueCodeValue extends Constraint
{
    public string $message = 'app.code.unique_value';
}
