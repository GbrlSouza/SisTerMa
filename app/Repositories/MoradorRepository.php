<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Morador;
use Core\Database;
use PDO;

class MoradorRepository
{
    public function findByCondominio(int $condominioId): array
    {
        $stmt = Database::getInstance()->prepare(
            'SELECT * FROM moradores WHERE condominio_id = ? AND ativo = 1 ORDER BY nome'
        );
        $stmt->execute([$condominioId]);
        return array_map([$this, 'hydrate'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function findById(int $id): ?Morador
    {
        $stmt = Database::getInstance()->prepare('SELECT * FROM moradores WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }

    public function findByUsuario(int $usuarioId): ?Morador
    {
        $stmt = Database::getInstance()->prepare(
            'SELECT * FROM moradores WHERE usuario_id = ? AND ativo = 1 LIMIT 1'
        );
        $stmt->execute([$usuarioId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }

    public function create(array $data): int
    {
        $stmt = Database::getInstance()->prepare(
            'INSERT INTO moradores (condominio_id, nome, email, cpf, telefone, bloco, unidade) 
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['condominio_id'],
            $data['nome'],
            $data['email'] ?? null,
            $data['cpf'] ?? null,
            $data['telefone'] ?? null,
            $data['bloco'] ?? null,
            $data['unidade'] ?? null,
        ]);
        return (int) Database::getInstance()->lastInsertId();
    }

    public function vincularUsuario(int $moradorId, int $usuarioId): void
    {
        $stmt = Database::getInstance()->prepare(
            'UPDATE moradores SET usuario_id = ? WHERE id = ?'
        );
        $stmt->execute([$usuarioId, $moradorId]);
    }

    protected function hydrate(array $row): Morador
    {
        return new Morador(
            (int) $row['id'],
            (int) $row['condominio_id'],
            $row['usuario_id'] ? (int) $row['usuario_id'] : null,
            $row['nome'],
            $row['email'] ?? null,
            $row['cpf'] ?? null,
            $row['telefone'] ?? null,
            $row['bloco'] ?? null,
            $row['unidade'] ?? null,
            (bool) $row['ativo'],
            $row['created_at'],
            $row['updated_at'],
        );
    }
}
