<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class CalendarType extends AbstractType {

    const MONTHS = [
        'january' => 31,
        'february' => 29,
        'march' => 31,
        'april' => 30,
        'mai' => 31,
        'june' => 30,
        'july' => 31,
        'august' => 31,
        'september' => 30,
        'october' => 31,
        'november' => 30,
        'december' => 31,
    ];

    public function buildForm(FormBuilderInterface $builder, array $options) {

        foreach (self::MONTHS as $month => $days) {
            for ($day = 1; $day <= $days; $day++) {
                for ($prayer = 1; $day <= 6; $prayer++) {
                    $builder->add("day_$month" . "_" . "$day" . "_" . $prayer, TimeType::class);
                }
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver) {
        
    }

}
