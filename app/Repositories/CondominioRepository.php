<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Condominio;
use Core\Database;
use PDO;

class CondominioRepository
{
    public function findAll(?int $limit = null): array
    {
        $sql = 'SELECT c.*, p.nome as plano_nome FROM condominios c 
                LEFT JOIN planos p ON c.plano_id = p.id ORDER BY c.nome';
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        $stmt = Database::getInstance()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?Condominio
    {
        $stmt = Database::getInstance()->prepare('SELECT * FROM condominios WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }

    public function create(array $data): int
    {
        $stmt = Database::getInstance()->prepare(
            'INSERT INTO condominios (plano_id, nome, cnpj, endereco, cidade, estado, cep, telefone) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['plano_id'],
            $data['nome'],
            $data['cnpj'] ?? null,
            $data['endereco'] ?? null,
            $data['cidade'] ?? null,
            $data['estado'] ?? null,
            $data['cep'] ?? null,
            $data['telefone'] ?? null,
        ]);
        return (int) Database::getInstance()->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = Database::getInstance()->prepare(
            'UPDATE condominios SET plano_id=?, nome=?, cnpj=?, endereco=?, cidade=?, estado=?, cep=?, telefone=?, status=? WHERE id=?'
        );
        $stmt->execute([
            $data['plano_id'],
            $data['nome'],
            $data['cnpj'] ?? null,
            $data['endereco'] ?? null,
            $data['cidade'] ?? null,
            $data['estado'] ?? null,
            $data['cep'] ?? null,
            $data['telefone'] ?? null,
            $data['status'] ?? 'ativo',
            $id,
        ]);
    }

    public function updatePagamentoStatus(int $id, string $status, ?string $vencimento = null): void
    {
        $stmt = Database::getInstance()->prepare(
            'UPDATE condominios SET pagamento_status = ?, pagamento_vencimento = ? WHERE id = ?'
        );
        $stmt->execute([$status, $vencimento, $id]);
    }

    protected function hydrate(array $row): Condominio
    {
        return new Condominio(
            (int) $row['id'],
            (int) $row['plano_id'],
            $row['nome'],
            $row['cnpj'] ?? null,
            $row['endereco'] ?? null,
            $row['cidade'] ?? null,
            $row['estado'] ?? null,
            $row['cep'] ?? null,
            $row['telefone'] ?? null,
            $row['status'],
            $row['pagamento_status'],
            $row['pagamento_vencimento'] ?? null,
            $row['created_at'],
            $row['updated_at'],
        );
    }
}
