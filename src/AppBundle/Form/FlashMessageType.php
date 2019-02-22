<?php

namespace AppBundle\Form;

use AppBundle\Entity\FlashMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
                'constraints' => [
                    new Length([
                        'max' => 160
                    ]),
                ],
                'attr' => [
                    'class' => 'keyboardInput',
                    'maxlength' => 160,
                    'rows' => 3,
                    'placeholder' => 'flashMessage.form.content.placeholder'
                ]
            ])
            ->add('orientation', ChoiceType::class, [
                'choices'=> [
                    'flashMessage.form.ltr'=>'ltr',
                    'flashMessage.form.rtl'=>'rtl'
                ]
            ])
            ->add('color', TextType::class, [
                'attr' => [
                    'style' => 'width: 100px',
                    'class'=>"jscolor {width:240, height:150, hash:true, position:'bottom', borderColor:'#000', backgroundColor:'#000'}"
                ]
            ])
            ->add('expire', DateType::class, [
                'widget' => 'single_text',
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
            'required' => false,
            'label_format' => 'flashMessage.form.%name%.label',
            'data_class' => FlashMessage::class
        ));
    }

}
