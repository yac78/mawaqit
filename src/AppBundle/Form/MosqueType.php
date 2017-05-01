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
                    'attr' => [
                        'placeholder' => 'mosque.name',
                        'class' => 'form-control margin-bottom-10',
                    ]
                ])
                ->add('associationName', null, [
                    'attr' => [
                        'placeholder' => 'global.association_name',
                        'class' => 'form-control margin-bottom-10',
                    ]
                ])
                ->add('phone', null, [
                    'attr' => [
                        'placeholder' => 'global.phone',
                        'class' => 'form-control margin-bottom-10',
                    ]
                ])
                ->add('email', EmailType::class, [
                    'attr' => [
                        'placeholder' => 'global.email',
                        'class' => 'form-control margin-bottom-10',
                    ]
                ])
                ->add('address', null, [
                    'attr' => [
                        'placeholder' => 'global.address',
                        'class' => 'form-control margin-bottom-10',
                    ]
                ])
                ->add('city', null, [
                    'attr' => [
                        'placeholder' => 'global.city',
                        'class' => 'form-control margin-bottom-10',
                    ]
                ])
                ->add('zipcode', null, [
                    'attr' => [
                        'placeholder' => 'global.zipcode',
                        'class' => 'form-control margin-bottom-10',
                    ]
                ])
                ->add('country', null, [
                    'attr' => [
                        'placeholder' => 'global.country',
                        'class' => 'form-control margin-bottom-10',
                    ]
                ])
                ->add('rib', null, [
                    'attr' => [
                        'placeholder' => 'global.rib',
                        'class' => 'form-control margin-bottom-10',
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
