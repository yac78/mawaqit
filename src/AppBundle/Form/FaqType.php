<?php

namespace AppBundle\Form;

use AppBundle\Entity\Faq;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;


class FaqType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('question', null, [
                'required' => true,
                'constraints' => [
                    new Length([
                        'max' => 1024
                    ])
                ],
                'attr' => [
                    'maxlength' => "1024",
                ]
            ])
            ->add('answer', TextareaType::class, [
                'required' => true,
                'attr' => [
                    'class' => "tinymce",
                ],
            ])
            ->add('enabled', CheckboxType::class, [
                'required' => false
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
            'label_format' => 'faq.form.%name%.label',
            'data_class' => Faq::class
        ));
    }

}
