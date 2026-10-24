<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

final class JwtCookieManager
{
    public function __construct(
        private readonly JWTTokenManagerInterface $tokenManager,
        #[Autowire('%app.jwt_ttl%')]
        private readonly int $ttl,
        #[Autowire('%app.jwt_cookie%')]
        private readonly string $cookieName,
    ) {
    }

    public function login(Response $response, UserInterface $user, Request $request): Response
    {
        $response->headers->setCookie(Cookie::create(
            $this->cookieName,
            $this->tokenManager->create($user),
            time() + $this->ttl,
            '/',
            null,
            $request->isSecure(),
            true,
            false,
            Cookie::SAMESITE_LAX,
        ));

        return $response;
    }

    public function logout(Response $response): Response
    {
        $response->headers->clearCookie($this->cookieName, '/', null, false, true, Cookie::SAMESITE_LAX);

        return $response;
    }
}
