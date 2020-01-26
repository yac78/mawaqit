<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class DstDatesType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dstSummerDate', DateType::class, [
                'required' => false,
                'widget' => 'choice',
            ])
            ->add('dstWinterDate', DateType::class, [
                'required' => false,
                'widget' => 'choice',
            ])
            ->add('country', CountryType::class, [
                'placeholder' => 'Tous les pays',
                'label' => 'country',
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
        $resolver->setDefaults(array(
            'label_format' => 'configuration.form.%name%.label'
        ));
    }

}
