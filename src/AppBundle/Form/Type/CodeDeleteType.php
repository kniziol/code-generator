<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Form's type used to build form for codes' deletion
 *
 * @author    Krzysztof Niziol <krzysztof.niziol@meritoo.pl>
 * @copyright Meritoo.pl
 */
class CodeDeleteType extends AbstractType
{
    /**
     * Name of the textarea field used to enter values of codes
     *
     * @var string
     */
    const TEXT_AREA_NAME = 'codes_values';

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::TEXT_AREA_NAME, TextareaType::class, [
                'label'       => 'label.delete_codes',
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
            ]);
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'AppBundle',
        ]);
    }
}
