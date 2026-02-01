<?php

declare(strict_types=1);

namespace App\Middlewares;

use Core\Http\Request;
use Core\Http\Response;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): Response
    {
        if (empty($_SESSION['user_id'])) {
            $_SESSION['_flash']['error'] = 'Faça login para acessar.';
            return new Response('', 302, ['Location' => '/login']);
        }
        return $next($request);
    }
}
