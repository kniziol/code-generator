<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CodeDeleteType extends AbstractType
{
    public const TEXT_AREA_NAME = 'codes_values';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(self::TEXT_AREA_NAME, TextareaType::class, [
                'label'       => 'label.delete_codes',
                'attr'        => [
                    'class' => 'main-field',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 9]),
                ],
            ])
            ->add('submit_button', SubmitType::class, [
                'label' => 'action.delete',
                'attr'  => [
                    'class' => 'btn btn-danger',
                ],
            ])
        ;
    }
}
