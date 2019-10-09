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
                    'placeholder' => 'searchByKeyword'
                ]
            ])
            ->add('type', ChoiceType::class, [
                'label' => false,
                'constraints' => new Choice(["choices" => array_merge(["ALL"], Mosque::TYPES)]),
                'placeholder' => 'mosque_search.form.type.placeholder',
                'choices' => [
                    "mosque.types.all" => "ALL",
                    "mosque.types.MOSQUE" => Mosque::TYPE_MOSQUE,
                    "mosque.types.HOME" => Mosque::TYPE_HOME,
                    "mosque.types.STORE" => Mosque::TYPE_STORE,
                    "mosque.types.SCHOOL" => Mosque::TYPE_SCHOOL
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
                    "mosque.statuses.SCREEN_PHOTO_ADDED",
                ], Mosque::STATUSES)
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
            ->add('save', SubmitType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'btn btn-default fa fa-search',
                    'style' => 'padding:0.9rem 2rem',
                ]
            ])
            ;

        $builder->get('city')->resetViewTransformers();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'attr' => [
                'class' => 'form-inline'
            ],
            'allow_extra_fields' => true,
            'csrf_protection' => false,
            'required' => false
        ));
    }

    public function getBlockPrefix()
    {
        return '';
    }

}
