<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Mosque;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MosqueType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', null, [
                    'attr' => [
                        'placeholder' => 'toto',
                        'class' => 'form-control',
                    ]
                ])
                ->add('save', SubmitType::class, [
                    'attr' => [
                        'class' => 'btn btn-lg btn-primary',
                    ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Mosque::class
        ));
    }

}
