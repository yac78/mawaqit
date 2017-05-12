<?php

namespace AppBundle\Form;

use AppBundle\Form\PrayerType;
use AppBundle\Entity\Configuration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\DataTransformer\PrayerTransformer;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use AppBundle\Service\GoogleService;

class ConfigurationType extends AbstractType {

    /**
     *
     * @var GoogleService 
     */
    private $googleService;

    public function __construct(GoogleService $googleService) {
        $this->googleService = $googleService;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('lang', ChoiceType::class, [
                    'label' => 'configuration.form.lang',
                    'choices' => Configuration::LANG_CHOICES
                ])
                ->add('jumuaTime', TimeType::class, [
                    'input' => 'string',
                    'widget' => 'choice',
                    'label' => 'configuration.form.jumuaTime.label',
                ])
                ->add('aidTime', TimeType::class, [
                    'input' => 'string',
                    'widget' => 'choice',
                    'label' => 'configuration.form.aidTime.label'
                ])
                ->add('imsakNbMinBeforeFajr', IntegerType::class, [
                    'label' => 'configuration.form.imsakNbMinBeforeFajr'
                ])
                ->add('maximumIshaTimeForNoWaiting', TimeType::class, [
                    'input' => 'string',
                    'widget' => 'choice',
                    'label' => 'configuration.form.maximumIshaTimeForNoWaiting.label'
                ])
                ->add('waitingTimes', PrayerType::class, [
                    'sub_type' => IntegerType::class
                ])
                ->add('adjustedTimes', PrayerType::class, [
                    'sub_type' => IntegerType::class
                ])
                ->add('fixedTimes', PrayerType::class, [
                    'sub_type' => TimeType::class
                ])
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
                ->add('sourceCalcul', ChoiceType::class, [
                    'label' => 'configuration.form.sourceCalcul',
                    'choice_translation_domain' => true,
                    'choices' => array_combine(Configuration::SOURCE_CHOICES, Configuration::SOURCE_CHOICES)
                ])
                ->add('prayerMethod', ChoiceType::class, [
                    'label' => 'configuration.form.prayerMethod',
                    'choice_translation_domain' => true,
                    'choices' => array_combine(Configuration::METHOD_CHOICES, Configuration::METHOD_CHOICES)
                ])
                ->add('fajrDegree', IntegerType::class, [
                    'label' => 'configuration.form.fajrDegree.label',
                    'attr' => [
                        'placeholder' => 'configuration.form.fajrDegree.placeholder'
                    ]
                ])
                ->add('ishaDegree', IntegerType::class, [
                    'label' => 'configuration.form.ishaDegree.label',
                    'attr' => [
                        'placeholder' => 'configuration.form.ishaDegree.placeholder'
                    ]
                ])
                ->add('iqamaDisplayTime', IntegerType::class, [
                    'label' => 'configuration.form.iqamaDisplayTime.label',
                    'attr' => [
                        'placeholder' => 'configuration.form.iqamaDisplayTime.placeholder'
                    ]
                ])
                ->add('azanDuaDisplayTime', IntegerType::class, [
                    'label' => 'configuration.form.azanDuaDisplayTime.label',
                    'attr' => [
                        'placeholder' => 'configuration.form.azanDuaDisplayTime.placeholder'
                    ]
                ])
                ->add('calendar')
                ->add('save', SubmitType::class, [
                    'label' => 'global.save',
                    'attr' => [
                        'class' => 'btn btn-lg btn-primary',
                    ]
                ])
                ->addEventListener(FormEvents::POST_SUBMIT, array($this, 'onPostSetData'))
        ;


        $builder->get('waitingTimes')
                ->addModelTransformer(new PrayerTransformer(IntegerType::class));
        $builder->get('adjustedTimes')
                ->addModelTransformer(new PrayerTransformer(IntegerType::class));
        $builder->get('fixedTimes')
                ->addModelTransformer(new PrayerTransformer(TimeType::class));
    }

    public function onPostSetData(FormEvent $event) {
        $configuration = $event->getData();
        if ($configuration->getSourceCalcul() === Configuration::SOURCE_API) {
            $position = $this->googleService->getPosition($configuration->getMosque()->getCityZipCode());
            $configuration->setLongitude($position->lng);
            $configuration->setLatitude($position->lat);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Configuration::class,
            'allow_extra_fields' => true,
            'required' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'appbundle_configuration';
    }

}
