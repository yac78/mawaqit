<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MosqueSyncType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', IntegerType::class, [
                'required' => true,
                'label' => "mosqueScreen.fillId",
                'attr' => [
                    'help' => "mosqueScreen.fillIdHelp",
                    'class' => 'keyboardInput'
                ]
            ])->add('login', EmailType::class, [
                'required' => true,
                'label' => "mosqueScreen.login",
                'attr' => [
                    'help' => "mosqueScreen.loginHelp",
                    'class' => 'keyboardInput'
                ]
            ])->add('password', PasswordType::class, [
                'required' => true,
                'label' => "mosqueScreen.password",
                'attr' => [
                    'help' => "mosqueScreen.passwordHelp",
                    'class' => 'keyboardInput'
                ]
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }

}
