<?php

declare(strict_types=1);

namespace App\Middlewares;

use Core\Http\Request;
use Core\Http\Response;

class CsrfMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): Response
    {
        $token = $request->post('_token') ?? $request->getHeader('X-CSRF-TOKEN');
        if (!$token || !hash_equals($_SESSION['_csrf_token'] ?? '', $token)) {
            $_SESSION['_flash']['error'] = 'Token de segurança inválido. Tente novamente.';
            return new Response('', 302, ['Location' => $request->getPath() ?: '/login']);
        }
        return $next($request);
    }
}
