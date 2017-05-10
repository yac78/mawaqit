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
                ->add('fajr', $options["sub_type"])
                ->add('zuhr', $options["sub_type"])
                ->add('asr', $options["sub_type"])
                ->add('maghreb', $options["sub_type"])
                ->add('isha', $options["sub_type"])
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
