<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use AppBundle\Entity\Configuration;

class ConfigurationValidator extends ConstraintValidator
{

    /**
     * @param Configuration $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        // validate degrees
        if ($value->getSourceCalcul() === Configuration::SOURCE_API && $value->getPrayerMethod() === Configuration::METHOD_CUSTOM) {
            if (empty($value->getFajrDegree()) || empty($value->getIshaDegree())) {
                $this->context->buildViolation($constraint->m1)->addViolation();
            }
        }

        // validate dst dates
        if ($value->getDst() === 1 && ($value->getDstSummerDate() === null || $value->getDstWinterDate() === null)) {
            $this->context->buildViolation($constraint->m2)->addViolation();
        }
    }

}
