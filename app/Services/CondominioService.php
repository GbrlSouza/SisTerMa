<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CondominioRepository;

class CondominioService
{
    public function __construct(
        protected CondominioRepository $repository = new CondominioRepository()
    ) {}

    public function isPagamentoBloqueado(int $condominioId): bool
    {
        $condominio = $this->repository->findById($condominioId);
        if (!$condominio) {
            return true;
        }
        return in_array($condominio->pagamentoStatus, ['vencido', 'bloqueado']);
    }
}
