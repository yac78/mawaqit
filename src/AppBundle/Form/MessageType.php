<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use AppBundle\Entity\Message;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {


        $builder
                ->add('title', null, [
                    'label' => 'title',
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'message.form.title.placeholder',
                        'maxlength' => "50",
                    ]
                ])
                ->add('content', TextareaType::class, [
                    'label' => 'content',
                    'attr' => [
                        'placeholder' => 'message.form.content.placeholder',
                        'maxlength' => "160",
                        'rows' => "6"
                    ],
                ])
                ->add('enabled', CheckboxType::class, [
                    'label' => 'enabled',
                    'required' => false
                ])
                ->add('save', SubmitType::class, [
                    'label' => 'save',
                    'attr' => [
                        'class' => 'btn btn-primary',
                    ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Message::class
        ));
    }

}
