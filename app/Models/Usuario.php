<?php

declare(strict_types=1);

namespace App\Models;

class Usuario
{
    public function __construct(
        public int $id,
        public ?int $condominioId,
        public string $email,
        public string $nome,
        public string $role,
        public bool $ativo,
        public ?string $ultimoAcesso,
        public string $createdAt,
        public string $updatedAt,
        public ?string $senha = null,
    ) {}
}
