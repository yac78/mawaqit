<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType ;
use Symfony\Component\Validator\Constraints\File;

class FileType extends AbstractType {

    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults([
            'required' => false,
            'download_uri' => false,
            'attr' =>  ['class' => 'form-control'],
            'constraints' => new File([
                'maxSize' => '10M',
                'mimeTypes' => [
                    'application/pdf',
                    'application/msword',
                    'application/vnd.ms-powerpoint',
                    'image/png',
                    'image/jpeg',
                ]
            ])
        ]);
    }

    public function getParent()
    {
        return VichImageType::class;
    }

}
