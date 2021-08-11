<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class CodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value', null, [
                'label' => 'label.code.your_code',
                'attr'  => [
                    'class' => 'main-field',
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'label.code.add',
                'attr'  => [
                    'class' => 'btn-primary',
                ],
            ])
        ;
    }
}
