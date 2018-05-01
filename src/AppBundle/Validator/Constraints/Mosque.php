<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Mosque extends Constraint {

    public $mandatoryAddrMsg = 'form.mosque.address_mandatory';

    public function getTargets() {
        return self::CLASS_CONSTRAINT;
    }

}
