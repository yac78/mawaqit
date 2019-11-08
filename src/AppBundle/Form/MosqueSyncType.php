<?php

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MosqueSyncType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', IntegerType::class, [
                'required' => false,
                'label' => "mosqueScreen.fillId",
                'attr' => [
                    'style' => "width: 200px",
                    'class' => 'keyboardInput'
                ]
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'allow_extra_fields' => true
        ));
    }

    public function getBlockPrefix()
    {
        return '';
    }

}
