<?php

declare(strict_types=1);

namespace App\Middlewares;

use Core\Http\Request;
use Core\Http\Response;

class MoradorMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): Response
    {
        if (($_SESSION['user_role'] ?? '') !== 'morador') {
            $_SESSION['_flash']['error'] = 'Acesso negado.';
            return new Response('', 302, ['Location' => '/login']);
        }
        return $next($request);
    }
}
