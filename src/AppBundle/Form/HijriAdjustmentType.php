<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class HijriAdjustmentType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('hijriAdjustment', ChoiceType::class, [
                'choices' => [-2 => -2, -1 => -1, 0 => 0, 1 => 1, 2 => 2],
                'label' => false,
                'placeholder' => 'Choisir une valeur',
                'required' =>  true
            ])

            ->add('save', SubmitType::class, [
                'label' => 'save',
                'attr' => [
                    'class' => 'btn btn-primary',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }

}
