<?php

namespace AppBundle\Form;

use AppBundle\Entity\FlashMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\GreaterThan;


class FlashMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 140
                    ]),
                ],
                'attr' => [
                    'maxlength' => 140,
                    'row' => 3,
                    'placeholder' => 'flashMessage.form.content.placeholder'
                ]
            ])
            ->add('expire', DateTimeType::class, [
                'required' => false,
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'constraints' => [
                    new GreaterThan([
                        'value' => new \DateTime()
                    ])
                ],
                'attr' => [
                    'help' => 'flashMessage.form.expire.title',
                ]
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
            'label_format' => 'flashMessage.form.%name%.label',
            'data_class' => FlashMessage::class
        ));
    }

}
