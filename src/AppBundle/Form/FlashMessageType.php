<?php

namespace AppBundle\Form;

use AppBundle\Entity\FlashMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FlashMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'keyboardInput',
                    'maxlength' => 160,
                    'rows' => 3,
                    'placeholder' => 'flashMessage.form.content.placeholder'
                ]
            ])
            ->add('orientation', ChoiceType::class, [
                'choices' => [
                    'flashMessage.form.ltr' => 'ltr',
                    'flashMessage.form.rtl' => 'rtl'
                ]
            ])
            ->add('color', TextType::class, [
                'attr' => [

                    'class' => "jscolor {width:240, height:150, hash:true, position:'bottom', borderColor:'#000', backgroundColor:'#000'}"
                ]
            ])
            ->add('expire', DateType::class, [
                'required' => true,
                'widget' => 'single_text',
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

        // Work arround to fix a sf bug, it is shifting one day from expire date
        $builder->get('expire')
            ->addModelTransformer(new CallbackTransformer(
                function ($expire) {
                    return $expire;
                },
                function (?\DateTime $expire) {
                    if ($expire instanceof \DateTime) {
                        return $expire->modify("+1 day");
                    }
                    return $expire;
                }
            ));
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
