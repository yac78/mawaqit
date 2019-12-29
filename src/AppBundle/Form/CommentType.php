<?php

namespace AppBundle\Form;

use AppBundle\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('text', TextareaType::class, [
                'label' => 'Text',
                'required' => true,
                'attr' => [
                    'rows' => "3",
                    'style' => "min-height: auto"
                ],
            ])
            ->add('user', null, [
                'disabled' => true,
                'label' => 'Admin',
                'required' => true,
            ])
            ->add('createdAt', null, [
                'disabled' => true,
                'widget' => 'single_text',
                'label' => 'Date',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }

}
