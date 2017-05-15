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

        $timeOption = [];

        if ($options["sub_type"] === TextType::class) {
            $timeOption = [
                'attr' => [
                    'placeholder' => "hh:mm",
                    'pattern' => '\d{2}:\d{2}',
                    'maxlength' => 5,
                    'oninvalid' => "setCustomValidity('" . $this->translator->trans('configuration.form.time_invalid') . "')",
                    'onchange' => 'try {setCustomValidity("")} catch (e) {}'
                ]
            ];
        }

        $builder
                ->add('fajr', $options["sub_type"], array_merge($timeOption, [
                    'label' => 'fajr'
                ]))
                ->add('zuhr', $options["sub_type"], array_merge($timeOption, [
                    'label' => 'zuhr'
                ]))
                ->add('asr', $options["sub_type"], array_merge($timeOption, [
                    'label' => 'asr'
                ]))
                ->add('maghrib', $options["sub_type"], array_merge($timeOption, [
                    'label' => 'maghrib'
                ]))
                ->add('isha', $options["sub_type"], array_merge($timeOption, [
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
