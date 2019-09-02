<?php

namespace AppBundle\Form;

use AppBundle\Entity\Mosque;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MosqueSuspensionType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('status', HiddenType::class, [
                'data' => Mosque::STATUS_SUSPENDED,
            ])
            ->add('reason', ChoiceType::class, [
                'choices' => [
                    'mosque.suspensionReason.mosque_closed' => 'mosque_closed',
                    'mosque.suspensionReason.prayer_times_not_correct' => 'prayer_times_not_correct',
                    'mosque.suspensionReason.other' => 'other',
                ],
                'label' => 'Séléctionner une raison',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'save',
                'attr' => [
                    'class' => 'btn btn-primary',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'label_format' => 'mosque.form.%name%.label',
            'data_class' => Mosque::class,
        ));
    }

    public function getBlockPrefix()
    {
        return '';
    }

}
