<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class PrayerType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('fajr', $options["sub_type"],[
                    'label'=> 'fajr'
                ])
                ->add('zuhr', $options["sub_type"],[
                    'label'=> 'zuhr'
                ])
                ->add('asr', $options["sub_type"],[
                    'label'=> 'asr'
                ])
                ->add('maghrib', $options["sub_type"],[
                    'label'=> 'maghrib'
                ])
                ->add('isha', $options["sub_type"],[
                    'label'=> 'isha'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {

        $resolver->setDefaults(array(
            'sub_type' => IntegerType::class
        ));
        
        $resolver->setAllowedValues('sub_type', array(
            IntegerType::class,
            TimeType::class
        ));
    }

}
