<?php

declare(strict_types=1);

namespace App\Repositories;

use Core\Database;
use PDO;

class PlanoRepository
{
    public function findAll(): array
    {
        $stmt = Database::getInstance()->query(
            'SELECT * FROM planos WHERE ativo = 1 ORDER BY valor'
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = Database::getInstance()->prepare('SELECT * FROM planos WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
