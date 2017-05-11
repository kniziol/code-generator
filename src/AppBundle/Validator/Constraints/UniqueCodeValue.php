<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Constraint for unique value of code
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 *
 * @Annotation
 */
class UniqueCodeValue extends Constraint
{
    /**
     * Message used when value of code is not unique
     *
     * @var string
     */
    public $message = 'app.code.unique_value';
}
