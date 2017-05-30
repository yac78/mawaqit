<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Translation\TranslatorInterface;

class PrayerType extends AbstractType {

    /**
     *
     * @var Translator 
     */
    private $translator;

    public function __construct( TranslatorInterface $translator) {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $type = $options['sub_type'];
        unset($options['sub_type']);

        $builder
                ->add('fajr', $type, array_merge($options, [
                    'label' => 'fajr'
                ]))
                ->add('zuhr', $type, array_merge($options, [
                    'label' => 'zuhr'
                ]))
                ->add('asr', $type, array_merge($options, [
                    'label' => 'asr'
                ]))
                ->add('maghrib', $type, array_merge($options, [
                    'label' => 'maghrib'
                ]))
                ->add('isha', $type, array_merge($options, [
                    'label' => 'isha'
                ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {

        $resolver->setDefaults(array(
            'sub_type' => IntegerType::class
        ));

        $resolver->setAllowedValues('sub_type', array(
            IntegerType::class,
            TextType::class
        ));
    }

}
