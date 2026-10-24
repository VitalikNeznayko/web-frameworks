<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;

final class ApiRequest
{
    public static function matches(Request $request): bool
    {
        return str_starts_with($request->getPathInfo(), '/api/')
            || 'json' === $request->getPreferredFormat()
            || 'json' === $request->getContentTypeFormat();
    }
}
