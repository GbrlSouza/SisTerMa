<?php

declare(strict_types=1);

namespace App\Middlewares;

use Core\Http\Request;
use Core\Http\Response;

class GuestMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): Response
    {
        if (!empty($_SESSION['user_id'])) {
            $role = $_SESSION['user_role'] ?? '';
            $redirect = match ($role) {
                'admin_master' => '/admin',
                'sindico' => '/sindico',
                default => '/morador',
            };
            return new Response('', 302, ['Location' => $redirect]);
        }
        return $next($request);
    }
}
