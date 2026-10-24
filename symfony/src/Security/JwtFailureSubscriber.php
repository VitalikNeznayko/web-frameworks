<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class JwtFailureSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly JwtCookieManager $cookieManager,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::JWT_INVALID => 'onFailure',
            Events::JWT_EXPIRED => 'onFailure',
        ];
    }

    public function onFailure(AuthenticationFailureEvent $event): void
    {
        $request = $event->getRequest();
        if (null === $request || ApiRequest::matches($request)) {
            return;
        }

        $event->setResponse($this->cookieManager->logout(
            new RedirectResponse($this->urlGenerator->generate('app_login', ['expired' => 1])),
        ));
    }
}
