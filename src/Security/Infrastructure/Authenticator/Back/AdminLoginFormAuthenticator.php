<?php

declare(strict_types=1);

namespace App\Security\Infrastructure\Authenticator\Back;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Throwable;

class AdminLoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    public function __construct(
        private UserProviderInterface $userProvider,
        private UserPasswordHasherInterface $passwordHasher,
        private CsrfTokenManagerInterface $csrfTokenManager,
        private RouterInterface $router
    ) {
    }

    public function supports(Request $request): bool
    {
        return $request->isMethod("POST") && $request->attributes->get("_route") === "back.login";
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate("back.login");
    }

    public function authenticate(Request $request): Passport
    {
        $token = (string) $request->request->get("csrf_token");
        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken("authenticate", $token))) {
            throw new InvalidCsrfTokenException("Invalid CSRF token.");
        }

        $email = (string) $request->request->get("email");
        try {
            /** @var UserInterface&PasswordAuthenticatedUserInterface $admin */
            $admin = $this->userProvider->loadUserByIdentifier($email);
        } catch (Throwable) {
            throw new CustomUserMessageAuthenticationException("Invalid credentials.");
        }

        $password = (string) $request->request->get("password");
        if (!$this->passwordHasher->isPasswordValid($admin, $password)) {
            throw new CustomUserMessageAuthenticationException("Invalid credentials.");
        }

        return new Passport(new UserBadge($email), new PasswordCredentials($password), [
            new CsrfTokenBadge("authenticate", $token),
        ]);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->router->generate("back.dashboard"));
    }
}
