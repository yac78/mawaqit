<?php

namespace AppBundle\Form;

use AppBundle\Entity\Configuration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigurationType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('lang', ChoiceType::class, [
                    'label' => 'configuration.form.hijriDateEnabled'
                ])
                ->add('jumuaTime', TimeType::class, [
                    "input" => "string",
                    "widget" => "choice",
                    'label' => 'configuration.form.hijriDateEnabled.label',
                    "attr" => [
                        "placeholder" => "configuration.form.hijriDateEnabled.placeholder"
                    ]
                ])
                ->add('aidTime', TimeType::class, [
                    "input" => "string",
                    "widget" => "choice",
                    'label' => 'configuration.form.hijriDateEnabled.label'
                ])
                ->add('imsakNbMinBeforeFajr', IntegerType::class, [
                    'label' => 'configuration.form.imsakNbMinBeforeFajr'
                ])
                ->add('maximumIshaTimeForNoWaiting', TimeType::class, [
                    "input" => "string",
                    "widget" => "choice",
                    'label' => 'configuration.form.maximumIshaTimeForNoWaiting.label',
                    "placeholder" => "configuration.form.maximumIshaTimeForNoWaiting.placeholder",
                    "attr" => [
                        'class' => 'form-control margin-bottom-10',
                    ]
                ])
                ->add('waitingTimes')
                ->add('adjustedTimes')
                ->add('fixedTimes')
                ->add('hijriAdjustment', IntegerType::class, [
                    'label' => 'configuration.form.hijriAdjustment'
                ])
                ->add('hijriDateEnabled', CheckboxType::class, [
                    'label' => 'configuration.form.hijriDateEnabled.label',
                ])
                ->add('duaAfterAzanEnabled', CheckboxType::class, [
                    'label' => 'configuration.form.duaAfterAzanEnabled.label',
                ])
                ->add('duaAfterPrayerEnabled', CheckboxType::class, [
                    'label' => 'configuration.form.duaAfterPrayerEnabled.label',
                ])
                ->add('androidAppEnabled', CheckboxType::class, [
                    'label' => 'configuration.form.androidAppEnabled.label',
                ])
                ->add('sourceCalcul')
                ->add('prayerMethod')
                ->add('latitude', IntegerType::class, [
                    'label' => 'configuration.form.latitude.label',
                    "attr" => [
                        "placeholder" => "configuration.form.latitude.placeholder"
                    ]
                ])
                ->add('longitude', IntegerType::class, [
                    'label' => 'configuration.form.longitude.label',
                    "attr" => [
                        "placeholder" => "configuration.form.longitude.placeholder"
                    ]
                ])
                ->add('fajrDegree', IntegerType::class, [
                    'label' => 'configuration.form.fajrDegree.label',
                    "attr" => [
                        "placeholder" => "configuration.form.fajrDegree.placeholder"
                    ]
                ])
                ->add('ishaDegree', IntegerType::class, [
                    'label' => 'configuration.form.ishaDegree.label',
                    "attr" => [
                        "placeholder" => "configuration.form.ishaDegree.placeholder"
                    ]
                ])
                ->add('iqamaDisplayTime', IntegerType::class, [
                    'label' => 'configuration.form.iqamaDisplayTime.label',
                    "attr" => [
                        "placeholder" => "configuration.form.iqamaDisplayTime.placeholder"
                    ]
                ])
                ->add('azanDuaDisplayTime', IntegerType::class, [
                    'label' => 'configuration.form.azanDuaDisplayTime.label',
                    "attr" => [
                        "placeholder" => "configuration.form.azanDuaDisplayTime.placeholder"
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

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Configuration::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'appbundle_configuration';
    }

}
