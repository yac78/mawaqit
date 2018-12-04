<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('apiUseComment', TextareaType::class)
            ->add('apiAccessToken', TextType::class)
            ->add('apiQuota', NumberType::class)
            ->add('apiCallNumber', NumberType::class, [
                'attr' => [
                    'readonly' => true
                ]
            ])
            ->add('enabled', CheckboxType::class);
    }

    public function getBlockPrefix()
    {
        return '';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'label_format' => 'user.form.%name%.label',
            'data_class' => User::class,
        ));
    }

}
