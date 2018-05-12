<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use AppBundle\Entity\Mosque;

class MosqueValidator extends ConstraintValidator
{
    /**
     * @param Mosque $mosque
     * @param Constraint $constraint
     */
    public function validate($mosque, Constraint $constraint)
    {
        if ($mosque->getType() === Mosque::TYPE_MOSQUE) {
            $this->context->buildViolation($constraint->mandatoryAddrMsg)->addViolation();
        }
    }
}
