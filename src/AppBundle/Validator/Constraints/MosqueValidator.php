<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\Mosque;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

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

    const MAX_DEFAULT_MOSQUE_QUOTA = 10;

    /**
     * @param Mosque $mosque
     * @param Constraint $constraint
     */
    public function validate($mosque, Constraint $constraint)
    {
        /**
         * @var User $user
         */
        $user = $this->tokenStorage->getToken()->getUser();

        // validate max authorized mosques
        $quota = self::MAX_DEFAULT_MOSQUE_QUOTA;
        if($user->getMosqueQuota() !== null){
            $quota = $user->getMosqueQuota();
        }

        if (!$user->isAdmin() && $user->getMosques()->count() >= $quota) {
            $this->context->buildViolation($constraint->maxReachedMsg)->addViolation();
        }

        // validate justificatory
        if (!$user->isAdmin() && $mosque->isMosque() && !$mosque->isValidated()) {
            if (!$mosque->getJustificatoryfile()) {
                $this->context->buildViolation($constraint->justificatoryMandatory)->addViolation();
            }

            // validate main photo
            if (!$mosque->getFile1()) {
                $this->context->buildViolation($constraint->mainImageMandatory)->addViolation();
            }
        }
    }
}
