<?php

declare(strict_types=1);

namespace App\Repositories;

use Core\Database;
use PDO;

class PagamentoRepository
{
    public function findPendenteByCondominio(int $condominioId): ?array
    {
        $stmt = Database::getInstance()->prepare(
            'SELECT * FROM pagamentos WHERE condominio_id = ? AND status = ? ORDER BY vencimento DESC LIMIT 1'
        );
        $stmt->execute([$condominioId, 'pendente']);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findByReferenceId(string $referenceId): ?array
    {
        $stmt = Database::getInstance()->prepare(
            'SELECT * FROM pagamentos WHERE picpay_reference_id = ? LIMIT 1'
        );
        $stmt->execute([$referenceId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): int
    {
        $stmt = Database::getInstance()->prepare(
            'INSERT INTO pagamentos (condominio_id, plano_id, valor, vencimento, status, picpay_reference_id, qr_code) 
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['condominio_id'],
            $data['plano_id'],
            $data['valor'],
            $data['vencimento'],
            $data['status'] ?? 'pendente',
            $data['picpay_reference_id'] ?? null,
            $data['qr_code'] ?? null,
        ]);
        return (int) Database::getInstance()->lastInsertId();
    }

    public function marcarComoPago(int $id): void
    {
        $stmt = Database::getInstance()->prepare(
            'UPDATE pagamentos SET status = ?, pago_em = NOW() WHERE id = ?'
        );
        $stmt->execute(['pago', $id]);
    }

    public function updatePicPayData(int $id, string $chargeId, ?string $qrCode): void
    {
        $stmt = Database::getInstance()->prepare(
            'UPDATE pagamentos SET picpay_charge_id = ?, qr_code = ? WHERE id = ?'
        );
        $stmt->execute([$chargeId, $qrCode, $id]);
    }
}
