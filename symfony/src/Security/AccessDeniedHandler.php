<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Twig\Environment;

final class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function __construct(private readonly Environment $twig)
    {
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
    {
        if (ApiRequest::matches($request)) {
            return new JsonResponse(['code' => Response::HTTP_FORBIDDEN, 'message' => 'Access denied'], Response::HTTP_FORBIDDEN);
        }

        return new Response($this->twig->render('security/403.html.twig'), Response::HTTP_FORBIDDEN);
    }
}
