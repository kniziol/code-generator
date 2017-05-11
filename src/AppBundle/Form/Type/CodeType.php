<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Code;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form's type used to build the "create / update code" form
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class CodeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
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
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => Code::class,
            'translation_domain' => 'AppBundle',
        ]);
    }
}
