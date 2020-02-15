<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEmailType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('subject', null, [
                'label' => 'Sujet',
                'required' => true,
            ])
            ->add('country', CountryType::class, [
                'label' => 'Pays',
                'required' => false,
            ])
            ->add('isApiUser', CheckboxType::class, [
                'label' => 'Utilisateurs API',
                'required' => false,
            ])
            ->add('hasMosque', CheckboxType::class, [
                'label' => 'Utilisateurs ayant une mosquée',
                'required' => false,
            ])
            ->add('content', TextareaType::class, [
                'label' => 'content',
                'required' => true,
                'attr' => [
                    'class' => "tinymce",
                    'rows' => "10"
                ],
            ])
            ->add('send', SubmitType::class, [
                'label' => 'send',
                'attr' => [
                    'class' => 'btn btn-primary',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }

}
