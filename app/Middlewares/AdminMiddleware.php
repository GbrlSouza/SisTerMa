<?php

declare(strict_types=1);

namespace App\Middlewares;

use Core\Http\Request;
use Core\Http\Response;

class AdminMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): Response
    {
        if (($_SESSION['user_role'] ?? '') !== 'admin_master') {
            $_SESSION['_flash']['error'] = 'Acesso negado.';
            return new Response('', 302, ['Location' => '/login']);
        }
        return $next($request);
    }
}
