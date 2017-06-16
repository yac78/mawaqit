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
use Symfony\Component\Validator\Constraints\NotBlank;

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
                        'title' => $this->translator->trans('configuration.form.jumuaTime.title'),
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
                        'title' => $this->translator->trans('configuration.form.aidTime.title'),
                        'placeholder' => 'hh:mm',
                        'pattern' => '\d{2}:\d{2}',
                        'maxlength' => '5',
                        'oninvalid' => "setCustomValidity('" . $this->translator->trans('configuration.form.time_invalid') . "')",
                        'onchange' => 'try {setCustomValidity("")} catch (e) {}'
                    ]
                ])
                ->add('imsakNbMinBeforeFajr', IntegerType::class, [
                    'label' => 'configuration.form.imsakNbMinBeforeFajr.label',
                    'attr' => [
                        'title' => $this->translator->trans('configuration.form.imsakNbMinBeforeFajr.title'),
                        'min' => 0
                    ]
                ])
                ->add('maximumIshaTimeForNoWaiting', null, [
                    'label' => 'configuration.form.maximumIshaTimeForNoWaiting.label',
                    'attr' => [
                        'title' => $this->translator->trans('configuration.form.maximumIshaTimeForNoWaiting.title'),
                        'placeholder' => 'hh:mm ex: 22:30',
                        'pattern' => '\d{2}:\d{2}',
                        'maxlength' => '5',
                        'oninvalid' => "setCustomValidity('" . $this->translator->trans('configuration.form.time_invalid') . "')",
                        'onchange' => 'try {setCustomValidity("")} catch (e) {}'
                    ]
                ])
                ->add('waitingTimes', PrayerType::class, [
                    'label' => 'configuration.form.waitingTimes.label',
                    'sub_options' => [
                        'required' => true,
                        'type' => IntegerType::class,
                        'constraints' => new NotBlank(['message' => "form.configuration.mandatory"]),
                        'attr' => [
                            'min' => 0
                        ]
                    ]
                ])
                ->add('adjustedTimes', PrayerType::class, [
                    'required' => true,
                    'label' => 'configuration.form.adjustedTimes.label',
                    'constraints' => new NotBlank(['message' => "form.configuration.mandatory"]),
                    'sub_options' => [
                        'type' => IntegerType::class
                    ]
                ])
                ->add('fixedTimes', PrayerType::class, [
                    'required' => false,
                    'label' => 'configuration.form.fixedTimes.label',
                    'attr' => [
                        'title' => $this->translator->trans('configuration.form.fixedTimes.title')
                    ],
                    'sub_options' => [
                        'type' => TextType::class,
                        'attr' => [
                            'placeholder' => "hh:mm",
                            'pattern' => '\d{2}:\d{2}',
                            'maxlength' => 5,
                            'oninvalid' => "setCustomValidity('" . $this->translator->trans('configuration.form.time_invalid') . "')",
                            'onchange' => 'try {setCustomValidity("")} catch (e) {}'
                        ]
                    ]
                ])
                ->add('duaAfterPrayerShowTimes', PrayerType::class, [
                    'label' => 'configuration.form.duaAfterPrayerShowTimes.label',
                    'sub_options' => [
                        'type' => IntegerType::class,
                        'constraints' => new NotBlank(['message' => "form.configuration.mandatory"]),
                        'required' => true,
                        'attr' => [
                            'min' => 5
                        ]
                    ],
                    'attr' => [
                        'title' => $this->translator->trans('configuration.form.duaAfterPrayerShowTimes.title'),
                    ],
                ])
                ->add('hijriAdjustment', IntegerType::class, [
                    'label' => 'configuration.form.hijriAdjustment.label'
                ])
                ->add('hijriDateEnabled', CheckboxType::class, [
                    'required' => false,
                    'label' => 'configuration.form.hijriDateEnabled.label',
                ])
                ->add('duaAfterAzanEnabled', CheckboxType::class, [
                    'required' => false,
                    'label' => 'configuration.form.duaAfterAzanEnabled.label',
                ])
                ->add('duaAfterPrayerEnabled', CheckboxType::class, [
                    'required' => false,
                    'label' => 'configuration.form.duaAfterPrayerEnabled.label',
                ])
                ->add('sourceCalcul', ChoiceType::class, [
                    'required' => true,
                    'label' => 'configuration.form.sourceCalcul.label',
                    'choice_translation_domain' => true,
                    'choices' => array_combine(Configuration::SOURCE_CHOICES, Configuration::SOURCE_CHOICES)
                ])
                ->add('prayerMethod', ChoiceType::class, [
                    'required' => true,
                    'label' => 'configuration.form.prayerMethod.label',
                    'choice_translation_domain' => true,
                    'choices' => array_combine(Configuration::METHOD_CHOICES, Configuration::METHOD_CHOICES)
                ])
                ->add('fajrDegree', IntegerType::class, [
                    'required' => false,
                    'label' => 'configuration.form.fajrDegree.label',
                    'attr' => [
                        'placeholder' => 'configuration.form.fajrDegree.placeholder'
                    ]
                ])
                ->add('ishaDegree', IntegerType::class, [
                    'required' => false,
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
                ->add('urlQrCodeEnabled', CheckboxType::class, [
                    'required' => false,
                    'label' => 'configuration.form.urlQrCodeEnabled.label',
                    'attr' => [
                        'title' => $this->translator->trans('configuration.form.urlQrCodeEnabled.title'),
                    ]
                ])
                ->add('smallScreen', CheckboxType::class, [
                    'required' => false,
                    'label' => 'configuration.form.smallScreen.label',
                    'attr' => [
                        'title' => $this->translator->trans('configuration.form.smallScreen.title'),
                    ]
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
        $builder->get('duaAfterPrayerShowTimes')
                ->addModelTransformer(new PrayerTransformer(IntegerType::class));
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
            'allow_extra_fields' => true
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'appbundle_configuration';
    }

}
