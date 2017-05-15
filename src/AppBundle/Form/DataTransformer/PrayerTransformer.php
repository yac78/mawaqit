<?php

namespace AppBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class PrayerTransformer implements DataTransformerInterface {

    /**
     *
     * @var string 
     */
    private $type;

    public function __construct($type) {
        $this->type = $type;
    }

    public function transform($values) {
        if ($this->type === TimeType::class) {
            return [
                "fajr" => $values[0] ,
                "zuhr" => $values[1] ,
                "asr" => $values[2] ,
                "maghrib" => $values[3] ,
                "isha" => $values[4]
            ];
        }

        return [
            "fajr" => (int) $values[0],
            "zuhr" => (int) $values[1],
            "asr" => (int) $values[2],
            "maghrib" => (int) $values[3],
            "isha" => (int) $values[4]
        ];
    }

    public function reverseTransform($prayerIssue) {

        if ($this->type === TimeType::class) {
            foreach ($prayerIssue as $key => $value) {
                if($value instanceof \DateTime) {
                    $value = $value->format('H:i');
                }
                $prayerIssue[$key] = $value;
            }
        }

        return $prayerIssue;
    }

}
