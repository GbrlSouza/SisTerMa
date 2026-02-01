<?php

declare(strict_types=1);

namespace App\Models;

class Morador
{
    public function __construct(
        public int $id,
        public int $condominioId,
        public ?int $usuarioId,
        public string $nome,
        public ?string $email,
        public ?string $cpf,
        public ?string $telefone,
        public ?string $bloco,
        public ?string $unidade,
        public bool $ativo,
        public string $createdAt,
        public string $updatedAt,
    ) {}
}
