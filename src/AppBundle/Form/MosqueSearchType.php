<?php

namespace AppBundle\Form;

use AppBundle\Entity\Configuration;
use AppBundle\Entity\Mosque;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;


class MosqueSearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', null, [
                'attr' => [
                    'style' => 'width: 80px',
                    'placeholder' => 'mosque_search.form.id.placeholder'
                ]
            ])
            ->add('word', null, [
                'attr' => [
                    'placeholder' => 'mosque_search.form.word.placeholder'
                ]
            ])
            ->add('department', null, [
                'attr' => [
                    'style' => 'width: 80px',
                    'placeholder' => 'mosque_search.form.department.placeholder'
                ]
            ])
            ->add('country', CountryType::class, [
                'placeholder' => 'mosque_search.form.country.placeholder'
            ])
            ->add('city', ChoiceType::class, [
                'placeholder' => 'mosque_search.form.city.placeholder'
            ])
            ->add('type', ChoiceType::class, [
                'constraints' => new Choice(["choices" => Mosque::TYPES]),
                'placeholder' => 'mosque_search.form.type.placeholder',
                'choices' => [
                    "mosque.types.all" => "ALL",
                    "mosque.types.mosque" => Mosque::TYPE_MOSQUE,
                    "mosque.types.home" => Mosque::TYPE_HOME
                ]
            ])->add('status', ChoiceType::class, [
                'constraints' => new Choice(["choices" => Mosque::STATUSES]),
                'placeholder' => 'mosque_search.form.status.placeholder',
                'choices' => array_combine([
                    "mosque.statuses.NEW",
                    "mosque.statuses.CHECK",
                    "mosque.statuses.VALIDATED",
                    "mosque.statuses.SUSPENDED",
                    "mosque.statuses.DUPLICATED",
                ], Mosque::STATUSES)
            ])->add('sourceCalcul', ChoiceType::class, [
                'constraints' => new Choice(["choices" => Configuration::SOURCE_CHOICES]),
                'placeholder' => 'mosque_search.form.sourceCalcul.placeholder',
                'choices' => array_combine([
                    "Automatique",
                    "Calendrier",
                ], Configuration::SOURCE_CHOICES)
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'required' => false,
            'label_format' => 'mosque_search.form.%name%.label',
        ));
    }

    public function getBlockPrefix()
    {
        return '';
    }

}
