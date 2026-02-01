<?php

declare(strict_types=1);

namespace App\Models;

class Condominio
{
    public function __construct(
        public int $id,
        public int $planoId,
        public string $nome,
        public ?string $cnpj,
        public ?string $endereco,
        public ?string $cidade,
        public ?string $estado,
        public ?string $cep,
        public ?string $telefone,
        public string $status,
        public string $pagamentoStatus,
        public ?string $pagamentoVencimento,
        public string $createdAt,
        public string $updatedAt,
    ) {}
}
