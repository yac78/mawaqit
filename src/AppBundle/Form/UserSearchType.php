<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserSearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('disabled', CheckboxType::class, [
                'label'=> 'disabledOnly',
            ])
            ->add('api-users', CheckboxType::class, [
                'label'=> 'Utilisateur api'
            ])
            ->add('admin', CheckboxType::class, [
                'label'=> 'Admins'
            ])
            ->add('search', null, [
                'label'=> false,
                'attr' => [
                    'placeholder' => 'search'
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'btn btn-default ml-1 fa fa-search',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'attr' => [
                'class' => 'navbar-form'
            ],
            'required' => false,
        ));
    }

    public function getBlockPrefix()
    {
        return '';
    }

}
