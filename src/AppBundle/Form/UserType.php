<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email'
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôles',
                'required' => false,
                'multiple' => true,
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ]
            ])
            ->add('apiAccessToken', TextType::class, [
                'required' => false,
                'label' => 'Token API',
                'attr' => [
                    'readonly' => true
                ]
            ])
            ->add('apiUseDescription', TextareaType::class, [
                'required' => false,
                'label' => 'Commentaire API',
                'attr' => [
                    'rows' => 3
                ]
            ])
            ->add('apiQuota', IntegerType::class, [
                'required' => false,
                'label' => 'Quota API'
            ])
            ->add('apiCallNumber', IntegerType::class, [
                'required' => false,
                'label' => "Nombre d'appl API aujourd'hui",
                'attr' => [
                    'readonly' => true
                ]
            ])
            ->add('enabled', CheckboxType::class, [
                'required' => false,
                'label' => 'Activé ?'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'save',
                'attr' => [
                    'class' => 'btn btn-primary',
                ]
            ])
            ->addEventListener(FormEvents::SUBMIT, array($this, 'onSubmit'));
        ;
    }

    public function onSubmit(FormEvent $event)
    {
        /** @var User $user */
        $user = $event->getData();
        if (null !== $user->getApiQuota() && null === $user->getApiAccessToken()) {
            $user->setApiAccessToken(Uuid::uuid4());
        }
    }

    public function getBlockPrefix()
    {
        return '';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'required' => true,
            'data_class' => User::class,
        ));
    }

}
