<?php

namespace AppBundle\Form;

use AppBundle\Entity\Configuration;
use AppBundle\Entity\Mosque;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;


class MosqueSearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', null, [
                'label' => false,
                'attr' => [
                    'style' => 'width: 80px',
                    'placeholder' => 'mosque_search.form.id.placeholder'
                ]
            ])
            ->add('word', null, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'mosque_search.form.word.placeholder'
                ]
            ])
            ->add('department', null, [
                'label' => false,
                'attr' => [
                    'style' => 'width: 80px',
                    'placeholder' => 'mosque_search.form.department.placeholder'
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => false,
                'placeholder' => 'mosque_search.form.country.placeholder',
                'attr' => [
                    'data-remote' => '/cities/-country-'
                ]
            ])
            ->add('city', ChoiceType::class, [
                'validation_groups' => false,
                'label' => false,
                'placeholder' => 'mosque_search.form.city.placeholder'
            ])
            ->add('type', ChoiceType::class, [
                'label' => false,
                'constraints' => new Choice(["choices" => Mosque::TYPES]),
                'placeholder' => 'mosque_search.form.type.placeholder',
                'choices' => [
                    "mosque.types.all" => "ALL",
                    "mosque.types.mosque" => Mosque::TYPE_MOSQUE,
                    "mosque.types.home" => Mosque::TYPE_HOME
                ]
            ])
            ->add('status', ChoiceType::class, [
                'label' => false,
                'constraints' => new Choice(["choices" => Mosque::STATUSES]),
                'placeholder' => 'mosque_search.form.status.placeholder',
                'choices' => array_combine([
                    "mosque.statuses.NEW",
                    "mosque.statuses.CHECK",
                    "mosque.statuses.VALIDATED",
                    "mosque.statuses.SUSPENDED",
                    "mosque.statuses.DUPLICATED",
                ], Mosque::STATUSES)
            ])
            ->add('sourceCalcul', ChoiceType::class, [
                'label' => false,
                'constraints' => new Choice(["choices" => Configuration::SOURCE_CHOICES]),
                'placeholder' => 'mosque_search.form.sourceCalcul.placeholder',
                'choices' => array_combine([
                    "Automatique",
                    "Calendrier",
                ], Configuration::SOURCE_CHOICES)
            ])
            ->add('save', SubmitType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'btn btn-default ml-1 fa fa-search',
                    'style' => 'padding:0.9rem',

                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'attr' => [
                'class' => 'navbar-form'
            ],
            'required' => false
        ));
    }

    public function getBlockPrefix()
    {
        return '';
    }

}
