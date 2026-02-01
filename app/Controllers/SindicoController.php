<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Http\Request;
use Core\Http\Response;
use App\Repositories\CondominioRepository;
use App\Repositories\MoradorRepository;
use App\Repositories\PagamentoRepository;
use App\Repositories\PlanoRepository;
use App\Services\PicPayService;
use Core\Database;

class SindicoController extends BaseController
{
    public function dashboard(Request $request): Response
    {
        $condominioId = (int) $_SESSION['condominio_id'];
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT COUNT(*) FROM moradores WHERE condominio_id = ? AND ativo = 1');
        $stmt->execute([$condominioId]);
        $totalMoradores = (int) $stmt->fetchColumn();
        $stmt = $db->prepare('SELECT COUNT(*) FROM avisos WHERE condominio_id = ? AND publicado = 1');
        $stmt->execute([$condominioId]);
        $totalAvisos = (int) $stmt->fetchColumn();
        $condominio = (new CondominioRepository())->findById($condominioId);
        return $this->view('sindico/dashboard', [
            'condominio' => $condominio,
            'totalMoradores' => $totalMoradores,
            'totalAvisos' => $totalAvisos,
        ]);
    }

    public function moradores(Request $request): Response
    {
        $condominioId = (int) $_SESSION['condominio_id'];
        $moradores = (new MoradorRepository())->findByCondominio($condominioId);
        return $this->view('sindico/moradores', ['moradores' => $moradores]);
    }

    public function criarMorador(Request $request): Response
    {
        $condominioId = (int) $_SESSION['condominio_id'];
        $nome = trim($request->post('nome') ?? '');
        $email = trim($request->post('email') ?? '');
        $senha = $request->post('senha') ?? '';
        if (!$nome) {
            $_SESSION['_flash']['error'] = 'Nome é obrigatório.';
            return $this->redirect('/sindico/moradores');
        }
        $moradorRepo = new MoradorRepository();
        $usuarioId = null;
        if ($email && strlen($senha) >= 6) {
            $usuarioRepo = new \App\Repositories\UsuarioRepository();
            if ($usuarioRepo->findByEmail($email)) {
                $_SESSION['_flash']['error'] = 'Este email já está em uso.';
                return $this->redirect('/sindico/moradores');
            }
            $usuarioId = $usuarioRepo->create([
                'condominio_id' => $condominioId,
                'email' => $email,
                'nome' => $nome,
                'senha' => $senha,
                'role' => 'morador',
            ]);
        }
        $moradorId = $moradorRepo->create([
            'condominio_id' => $condominioId,
            'nome' => $nome,
            'email' => $email ?: null,
            'cpf' => preg_replace('/\D/', '', $request->post('cpf') ?? '') ?: null,
            'telefone' => trim($request->post('telefone') ?? '') ?: null,
            'bloco' => trim($request->post('bloco') ?? '') ?: null,
            'unidade' => trim($request->post('unidade') ?? '') ?: null,
        ]);
        if ($usuarioId) {
            $moradorRepo->vincularUsuario($moradorId, $usuarioId);
        }
        $_SESSION['_flash']['success'] = 'Morador cadastrado.' . ($usuarioId ? ' Acesso liberado para login.' : '');
        return $this->redirect('/sindico/moradores');
    }

    public function avisos(Request $request): Response
    {
        $condominioId = (int) $_SESSION['condominio_id'];
        $stmt = Database::getInstance()->prepare(
            'SELECT a.*, u.nome as autor FROM avisos a 
             LEFT JOIN usuarios u ON a.usuario_id = u.id 
             WHERE a.condominio_id = ? ORDER BY a.created_at DESC'
        );
        $stmt->execute([$condominioId]);
        $avisos = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->view('sindico/avisos', ['avisos' => $avisos]);
    }

    public function criarAviso(Request $request): Response
    {
        $condominioId = (int) $_SESSION['condominio_id'];
        $titulo = trim($request->post('titulo') ?? '');
        $conteudo = trim($request->post('conteudo') ?? '');
        if (!$titulo || !$conteudo) {
            $_SESSION['_flash']['error'] = 'Título e conteúdo são obrigatórios.';
            return $this->redirect('/sindico/avisos');
        }
        $stmt = Database::getInstance()->prepare(
            'INSERT INTO avisos (condominio_id, usuario_id, titulo, conteudo, tipo) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $condominioId,
            $_SESSION['user_id'],
            $titulo,
            $conteudo,
            $request->post('tipo') ?? 'geral',
        ]);
        $_SESSION['_flash']['success'] = 'Aviso publicado.';
        return $this->redirect('/sindico/avisos');
    }

    public function reservas(Request $request): Response
    {
        $condominioId = (int) $_SESSION['condominio_id'];
        $stmt = Database::getInstance()->prepare(
            'SELECT r.*, m.nome as morador_nome, ac.nome as area_nome 
             FROM reservas r 
             LEFT JOIN moradores m ON r.morador_id = m.id 
             LEFT JOIN areas_comuns ac ON r.area_comum_id = ac.id 
             WHERE r.condominio_id = ? ORDER BY r.data_reserva DESC, r.hora_inicio DESC'
        );
        $stmt->execute([$condominioId]);
        $reservas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->view('sindico/reservas', ['reservas' => $reservas]);
    }

    public function financeiro(Request $request): Response
    {
        $condominioId = (int) $_SESSION['condominio_id'];
        $stmt = Database::getInstance()->prepare(
            'SELECT * FROM pagamentos WHERE condominio_id = ? ORDER BY vencimento DESC LIMIT 12'
        );
        $stmt->execute([$condominioId]);
        $pagamentos = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $condominio = (new CondominioRepository())->findById($condominioId);
        return $this->view('sindico/financeiro', [
            'pagamentos' => $pagamentos,
            'condominio' => $condominio,
        ]);
    }

    public function pagamento(Request $request): Response
    {
        $condominioId = (int) $_SESSION['condominio_id'];
        $condominio = (new CondominioRepository())->findById($condominioId);
        $pagamentoRepo = new PagamentoRepository();
        $pagamentoPendente = $pagamentoRepo->findPendenteByCondominio($condominioId);
        $plano = $condominio ? (new PlanoRepository())->findById($condominio->planoId) : null;
        return $this->view('sindico/pagamento', [
            'condominio' => $condominio,
            'pagamentoPendente' => $pagamentoPendente,
            'plano' => $plano,
        ]);
    }

    public function gerarPagamento(Request $request): Response
    {
        $condominioId = (int) $_SESSION['condominio_id'];
        $condominio = (new CondominioRepository())->findById($condominioId);
        if (!$condominio) {
            $_SESSION['_flash']['error'] = 'Condomínio não encontrado.';
            return $this->redirect('/sindico/pagamento');
        }
        $plano = (new PlanoRepository())->findById($condominio->planoId);
        if (!$plano) {
            $_SESSION['_flash']['error'] = 'Plano não encontrado.';
            return $this->redirect('/sindico/pagamento');
        }
        $pagamentoRepo = new PagamentoRepository();
        $existente = $pagamentoRepo->findPendenteByCondominio($condominioId);
        if ($existente) {
            $_SESSION['_flash']['info'] = 'Já existe um pagamento pendente.';
            return $this->redirect('/sindico/pagamento');
        }
        $vencimento = date('Y-m-d', strtotime('+5 days'));
        $referenceId = 'pag-' . $condominioId . '-' . time();
        $pagamentoId = $pagamentoRepo->create([
            'condominio_id' => $condominioId,
            'plano_id' => $condominio->planoId,
            'valor' => $plano['valor'],
            'vencimento' => $vencimento,
            'status' => 'pendente',
            'picpay_reference_id' => $referenceId,
        ]);
        $picpay = new PicPayService();
        $result = $picpay->gerarCobrancaPix(
            $referenceId,
            (float) $plano['valor'],
            'Assinatura ' . $plano['nome'] . ' - ' . $condominio->nome,
            $_SESSION['user_name'] ?? 'Síndico',
            $_SESSION['user_email'] ?? 'sindico@condominio.com',
        );
        $qrCode = $result['qrCodeBase64'] ?? $result['qrCode'] ?? null;
        if ($qrCode) {
            $pagamentoRepo->updatePicPayData($pagamentoId, $result['paymentId'] ?? '', $qrCode);
        }
        $_SESSION['_flash']['success'] = 'Cobrança PIX gerada. Escaneie o QR Code para pagar.';
        return $this->redirect('/sindico/pagamento');
    }
}
