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
                    'label' => 'association_name',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ])
                ->add('phone', null, [
                    'label' => 'phone',
                    'required' => true,
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ])
                ->add('email', EmailType::class, [
                    'label' => 'email',
                    'required' => true,
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ])
                ->add('address', null, [
                    'label' => 'address',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ])
                ->add('city', null, [
                    'label' => 'city',
                    'required' => true,
                    'attr' => [
                        'placeholder' => 'mosque.form.city.placeholder',
                        'class' => 'form-control',
                    ]
                ])
                ->add('zipcode', null, [
                    'label' => 'zipcode',
                    'required' => true,
                    'attr' => [
                        'placeholder' => 'mosque.form.zipcode.placeholder',
                        'class' => 'form-control',
                    ]
                ])
                ->add('country', null, [
                    'label' => 'country',
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ])
                ->add('rib', null, [
                    'label' => 'rib',
                    'attr' => [
                        'placeholder' => 'mosque.form.placeholder.rib',
                        'class' => 'form-control',
                    ]
                ])
                ->add('save', SubmitType::class, [
                    'label' => 'save',
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
