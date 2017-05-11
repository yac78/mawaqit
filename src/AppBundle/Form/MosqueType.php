<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use AppBundle\Entity\Mosque;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MosqueType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', null, [
                    'label' => 'mosque.name',
                    'required' => true,
                    'attr' => [
                        'placeholder' => 'mosque.form.placeholder.name',
                        'class' => 'form-control',
                    ]
                ])
                ->add('associationName', null, [
                    'label' => 'global.association_name',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ])
                ->add('phone', null, [
                    'label' => 'global.phone',
                    'required' => true,
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ])
                ->add('email', EmailType::class, [
                    'label' => 'global.email',
                    'required' => true,
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ])
                ->add('address', null, [
                    'label' => 'global.address',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ])
                ->add('city', null, [
                    'label' => 'global.city',
                    'required' => true,
                    'attr' => [
                        'placeholder' => 'mosque.form.placeholder.city',
                        'class' => 'form-control',
                    ]
                ])
                ->add('zipcode', null, [
                    'label' => 'global.zipcode',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ])
                ->add('country', null, [
                    'label' => 'global.country',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ])
                ->add('rib', null, [
                    'label' => 'global.rib',
                    'attr' => [
                        'placeholder' => 'mosque.form.placeholder.rib',
                        'class' => 'form-control',
                    ]
                ])
                ->add('save', SubmitType::class, [
                    'label' => 'global.save',
                    'attr' => [
                        'class' => 'btn btn-lg btn-primary',
                    ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Mosque::class
        ));
    }

}
