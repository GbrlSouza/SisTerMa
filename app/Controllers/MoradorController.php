<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Http\Request;
use Core\Http\Response;
use App\Repositories\MoradorRepository;
use Core\Database;

class MoradorController extends BaseController
{
    public function dashboard(Request $request): Response
    {
        $moradorId = (int) $_SESSION['morador_id'];
        $morador = (new MoradorRepository())->findById($moradorId);
        $condominioId = $morador?->condominioId ?? (int) $_SESSION['condominio_id'];
        $stmt = Database::getInstance()->prepare(
            'SELECT * FROM avisos WHERE condominio_id = ? AND publicado = 1 ORDER BY created_at DESC LIMIT 5'
        );
        $stmt->execute([$condominioId]);
        $avisos = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->view('morador/dashboard', [
            'morador' => $morador,
            'avisos' => $avisos,
        ]);
    }

    public function avisos(Request $request): Response
    {
        $condominioId = (int) $_SESSION['condominio_id'];
        $stmt = Database::getInstance()->prepare(
            'SELECT a.*, u.nome as autor FROM avisos a 
             LEFT JOIN usuarios u ON a.usuario_id = u.id 
             WHERE a.condominio_id = ? AND a.publicado = 1 ORDER BY a.created_at DESC'
        );
        $stmt->execute([$condominioId]);
        $avisos = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->view('morador/avisos', ['avisos' => $avisos]);
    }

    public function reservas(Request $request): Response
    {
        $moradorId = (int) $_SESSION['morador_id'];
        $condominioId = (int) $_SESSION['condominio_id'];
        $stmt = Database::getInstance()->prepare(
            'SELECT r.*, ac.nome as area_nome FROM reservas r 
             LEFT JOIN areas_comuns ac ON r.area_comum_id = ac.id 
             WHERE r.morador_id = ? AND r.condominio_id = ? ORDER BY r.data_reserva DESC'
        );
        $stmt->execute([$moradorId, $condominioId]);
        $reservas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $stmt = Database::getInstance()->prepare('SELECT * FROM areas_comuns WHERE condominio_id = ? AND ativo = 1');
        $stmt->execute([$condominioId]);
        $areas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->view('morador/reservas', ['reservas' => $reservas, 'areas' => $areas]);
    }

    public function boletos(Request $request): Response
    {
        $moradorId = (int) $_SESSION['morador_id'];
        $stmt = Database::getInstance()->prepare(
            'SELECT * FROM boletos WHERE morador_id = ? ORDER BY vencimento DESC'
        );
        $stmt->execute([$moradorId]);
        $boletos = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->view('morador/boletos', ['boletos' => $boletos]);
    }

    public function ocorrencias(Request $request): Response
    {
        $moradorId = (int) $_SESSION['morador_id'];
        $stmt = Database::getInstance()->prepare(
            'SELECT * FROM ocorrencias WHERE morador_id = ? ORDER BY created_at DESC'
        );
        $stmt->execute([$moradorId]);
        $ocorrencias = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->view('morador/ocorrencias', ['ocorrencias' => $ocorrencias]);
    }

    public function criarOcorrencia(Request $request): Response
    {
        $moradorId = (int) $_SESSION['morador_id'];
        $condominioId = (int) $_SESSION['condominio_id'];
        $titulo = trim($request->post('titulo') ?? '');
        $descricao = trim($request->post('descricao') ?? '');
        $tipo = $request->post('tipo') ?? 'reclamacao';
        if (!$titulo || !$descricao) {
            $_SESSION['_flash']['error'] = 'Preencha título e descrição.';
            return $this->redirect('/morador/ocorrencias');
        }
        $stmt = Database::getInstance()->prepare(
            'INSERT INTO ocorrencias (condominio_id, morador_id, titulo, descricao, tipo) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$condominioId, $moradorId, $titulo, $descricao, $tipo]);
        $_SESSION['_flash']['success'] = 'Ocorrência registrada com sucesso.';
        return $this->redirect('/morador/ocorrencias');
    }
}
