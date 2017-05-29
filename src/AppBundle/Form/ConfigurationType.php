<?php

namespace AppBundle\Form;

use AppBundle\Form\PrayerType;
use AppBundle\Entity\Configuration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
use Symfony\Component\Translation\TranslatorInterface;

class ConfigurationType extends AbstractType {

    /**
     *
     * @var GoogleService 
     */
    private $googleService;

    /**
     *
     * @var Translator 
     */
    private $translator;

    public function __construct(GoogleService $googleService, TranslatorInterface $translator) {
        $this->googleService = $googleService;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('jumuaTime', null, [
                    'label' => 'configuration.form.jumuaTime.label',
                    'attr' => [
                        'placeholder' => 'hh:mm',
                        'pattern' => '\d{2}:\d{2}',
                        'maxlength' => 5,
                        'oninvalid' => "setCustomValidity('" . $this->translator->trans('configuration.form.time_invalid') . "')",
                        'onchange' => 'try {setCustomValidity("")} catch (e) {}'
                    ]
                ])
                ->add('aidTime', null, [
                    'label' => 'configuration.form.aidTime.label',
                    'attr' => [
                        'placeholder' => 'hh:mm',
                        'pattern' => '\d{2}:\d{2}',
                        'maxlength' => '5',
                        'oninvalid' => "setCustomValidity('" . $this->translator->trans('configuration.form.time_invalid') . "')",
                        'onchange' => 'try {setCustomValidity("")} catch (e) {}'
                    ]
                ])
                ->add('imsakNbMinBeforeFajr', IntegerType::class, [
                    'label' => 'configuration.form.imsakNbMinBeforeFajr.label'
                ])
                ->add('maximumIshaTimeForNoWaiting', null, [
                    'label' => 'configuration.form.maximumIshaTimeForNoWaiting.label',
                    'attr' => [
                        'placeholder' => 'hh:mm',
                        'pattern' => '\d{2}:\d{2}',
                        'maxlength' => '5',
                        'oninvalid' => "setCustomValidity('" . $this->translator->trans('configuration.form.time_invalid') . "')",
                        'onchange' => 'try {setCustomValidity("")} catch (e) {}'
                    ]
                ])
                ->add('waitingTimes', PrayerType::class, [
                    'label' => 'configuration.form.waitingTimes.label',
                    'sub_type' => IntegerType::class
                ])
                ->add('adjustedTimes', PrayerType::class, [
                    'label' => 'configuration.form.adjustedTimes.label',
                    'sub_type' => IntegerType::class
                ])
                ->add('fixedTimes', PrayerType::class, [
                    'label' => 'configuration.form.fixedTimes.label',
                    'sub_type' => TextType::class
                ])
                ->add('hijriAdjustment', IntegerType::class, [
                    'label' => 'configuration.form.hijriAdjustment.label'
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
                    'label' => 'configuration.form.sourceCalcul.label',
                    'choice_translation_domain' => true,
                    'choices' => array_combine(Configuration::SOURCE_CHOICES, Configuration::SOURCE_CHOICES)
                ])
                ->add('prayerMethod', ChoiceType::class, [
                    'label' => 'configuration.form.prayerMethod.label',
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
                ])
                ->add('azanDuaDisplayTime', IntegerType::class, [
                    'label' => 'configuration.form.azanDuaDisplayTime.label',
                ])
                ->add('smallScreen', CheckboxType::class, [
                    'label' => 'configuration.form.smallScreen.label',
                ])
                ->add('backgroundColor', null, [
                    'label' => 'configuration.form.backgroundColor.label',
                ])
                ->add('calendar')
                ->add('save', SubmitType::class, [
                    'label' => 'save',
                    'attr' => [
                        'class' => 'btn btn-primary',
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
        /**
         * @var Configuration
         */
        $configuration = $event->getData();
        if ($configuration->getPrayerMethod() !== Configuration::METHOD_CUSTOM) {
            $configuration->setFajrDegree(null);
            $configuration->setIshaDegree(null);
        }

        if ($configuration->getSourceCalcul() === Configuration::SOURCE_API) {
            $position = $this->googleService->getPosition($configuration->getMosque()->getCityZipCode());
            $configuration->setLongitude($position->lng);
            $configuration->setLatitude($position->lat);
            $timezone = $this->googleService->getTimezoneOffset($position->lng, $position->lat);
            $configuration->setTimezone($timezone);
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
