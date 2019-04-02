<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Configuration extends Constraint {

    public $m1 = 'form.configuration.custom_prayer_method';
    public $m2 = 'form.configuration.dst_dates_mandatory';

    public function getTargets() {
        return self::CLASS_CONSTRAINT;
    }

}
