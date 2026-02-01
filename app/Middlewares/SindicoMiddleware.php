<?php

declare(strict_types=1);

namespace App\Middlewares;

use Core\Http\Request;
use Core\Http\Response;
use App\Services\CondominioService;

class SindicoMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): Response
    {
        if (($_SESSION['user_role'] ?? '') !== 'sindico') {
            $_SESSION['_flash']['error'] = 'Acesso negado.';
            return new Response('', 302, ['Location' => '/login']);
        }
        $path = $request->getPath();
        if (!str_starts_with($path, '/sindico/pagamento')) {
            $condominioId = (int) ($_SESSION['condominio_id'] ?? 0);
            if ($condominioId && (new CondominioService())->isPagamentoBloqueado($condominioId)) {
                $_SESSION['_flash']['warning'] = 'Pagamento em atraso. Regularize para acessar o sistema.';
                return new Response('', 302, ['Location' => '/sindico/pagamento']);
            }
        }
        return $next($request);
    }
}
