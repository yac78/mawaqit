<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use AppBundle\Entity\Mosque;

class MosqueValidator extends ConstraintValidator
{

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    const LIMIT_INSTALL = 10;
    /**
     * @param Mosque $mosque
     * @param Constraint $constraint
     */
    public function validate($mosque, Constraint $constraint)
    {
        if ($mosque->getType() === Mosque::TYPE_MOSQUE && empty($mosque->getAddress())) {
            $this->context->buildViolation($constraint->mandatoryAddrMsg)->addViolation();
        }

        /**
         * @var User $user
         */
        $user = $this->tokenStorage->getToken()->getUser();
        if (!$user->isAdmin() && $user->getMosques()->count() >= self::LIMIT_INSTALL) {
            $this->context->buildViolation($constraint->maxReachedMsg)->addViolation();
        }
    }
}
