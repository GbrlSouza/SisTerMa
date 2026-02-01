<?php

declare(strict_types=1);

namespace App\Middlewares;

use Core\Http\Request;
use Core\Http\Response;

class TenantMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): Response
    {
        $condominioId = $_SESSION['condominio_id'] ?? null;
        if (!$condominioId) {
            $_SESSION['_flash']['error'] = 'Condomínio não identificado.';
            return new Response('', 302, ['Location' => '/login']);
        }
        return $next($request);
    }
}
