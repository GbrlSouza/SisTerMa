<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Usuario;
use Core\Database;
use PDO;

class UsuarioRepository
{
    public function findByEmail(string $email): ?Usuario
    {
        $stmt = Database::getInstance()->prepare(
            'SELECT * FROM usuarios WHERE email = ? AND ativo = 1 LIMIT 1'
        );
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }

    public function findById(int $id): ?Usuario
    {
        $stmt = Database::getInstance()->prepare('SELECT * FROM usuarios WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }

    public function findByCondominio(int $condominioId): array
    {
        $stmt = Database::getInstance()->prepare(
            'SELECT * FROM usuarios WHERE condominio_id = ? ORDER BY nome'
        );
        $stmt->execute([$condominioId]);
        return array_map(fn($r) => $this->hydrate($r), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function create(array $data): int
    {
        $stmt = Database::getInstance()->prepare(
            'INSERT INTO usuarios (condominio_id, email, senha, nome, role) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['condominio_id'],
            $data['email'],
            password_hash($data['senha'], PASSWORD_DEFAULT),
            $data['nome'],
            $data['role'],
        ]);
        return (int) Database::getInstance()->lastInsertId();
    }

    public function updateUltimoAcesso(int $id): void
    {
        $stmt = Database::getInstance()->prepare(
            'UPDATE usuarios SET ultimo_acesso = NOW() WHERE id = ?'
        );
        $stmt->execute([$id]);
    }

    protected function hydrate(array $row): Usuario
    {
        return new Usuario(
            (int) $row['id'],
            $row['condominio_id'] ? (int) $row['condominio_id'] : null,
            $row['email'],
            $row['nome'],
            $row['role'],
            (bool) $row['ativo'],
            $row['ultimo_acesso'],
            $row['created_at'],
            $row['updated_at'],
            $row['senha'] ?? null,
        );
    }
}
