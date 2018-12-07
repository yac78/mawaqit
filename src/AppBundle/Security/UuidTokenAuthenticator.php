<?php

namespace AppBundle\Security;

use AppBundle\Entity\User;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;


class UuidTokenAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
{

    const TOKEN_NAME = 'Api-Access-Token';

    public function createToken(Request $request, $providerKey)
    {
        $apiAccessToken = $request->headers->get(self::TOKEN_NAME);

        if (!$apiAccessToken) {
            throw new AccessDeniedHttpException(self::TOKEN_NAME . ' header is required');
        }

        if (!Uuid::isValid($apiAccessToken)) {
            throw new AccessDeniedHttpException(self::TOKEN_NAME . ' header is not valid');
        }

        return new PreAuthenticatedToken(
            'anon.',
            $apiAccessToken,
            $providerKey
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken &&
            $token->getProviderKey() === $providerKey;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        $apiAccessToken = $token->getCredentials();

        $user = $userProvider->loadUserByUsername($apiAccessToken);

        if (!$user instanceof UserInterface) {
            throw new CustomUserMessageAuthenticationException(
                sprintf('No user found for token "%s".', $apiAccessToken)
            );
        }

        $this->checkQuota($user);

        return new PreAuthenticatedToken(
            $user,
            $apiAccessToken,
            $providerKey,
            $user->getRoles()
        );
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        throw $exception;
    }

    private function checkQuota(User $user)
    {
        if ($user->getApiCallNumber() >= $user->getApiQuota()) {
            throw new AccessDeniedHttpException(sprintf('You have reached your quota %s of %s allowed', $user->getApiCallNumber(), $user->getApiQuota()));
        }
    }

}
