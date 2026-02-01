<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Http\Request;
use Core\Http\Response;
use App\Repositories\CondominioRepository;
use App\Repositories\PlanoRepository;
use App\Repositories\UsuarioRepository;
use Core\Database;

class AdminController extends BaseController
{
    public function dashboard(Request $request): Response
    {
        $db = Database::getInstance();
        $stmt = $db->query('SELECT COUNT(*) FROM condominios');
        $totalCondominios = (int) $stmt->fetchColumn();
        $stmt = $db->query("SELECT COUNT(*) FROM condominios WHERE pagamento_status IN ('vencido', 'bloqueado')");
        $inadimplentes = (int) $stmt->fetchColumn();
        return $this->view('admin/dashboard', [
            'totalCondominios' => $totalCondominios,
            'inadimplentes' => $inadimplentes,
        ]);
    }

    public function condominios(Request $request): Response
    {
        $repo = new CondominioRepository();
        $condominios = $repo->findAll();
        return $this->view('admin/condominios', ['condominios' => $condominios]);
    }

    public function condominioForm(Request $request): Response
    {
        $planos = (new PlanoRepository())->findAll();
        return $this->view('admin/condominio-form', ['planos' => $planos]);
    }

    public function criarCondominio(Request $request): Response
    {
        $data = [
            'plano_id' => (int) $request->post('plano_id'),
            'nome' => trim($request->post('nome') ?? ''),
            'cnpj' => preg_replace('/\D/', '', $request->post('cnpj') ?? '') ?: null,
            'endereco' => trim($request->post('endereco') ?? '') ?: null,
            'cidade' => trim($request->post('cidade') ?? '') ?: null,
            'estado' => trim($request->post('estado') ?? '') ?: null,
            'cep' => preg_replace('/\D/', '', $request->post('cep') ?? '') ?: null,
            'telefone' => trim($request->post('telefone') ?? '') ?: null,
        ];
        if (!$data['nome']) {
            $_SESSION['_flash']['error'] = 'Nome é obrigatório.';
            return $this->redirect('/admin/condominios/criar');
        }
        $repo = new CondominioRepository();
        $condominioId = $repo->create($data);
        $_SESSION['_flash']['success'] = 'Condomínio criado. Cadastre o síndico.';
        return $this->redirect("/admin/condominios/{$condominioId}");
    }

    public function editarCondominio(Request $request): Response
    {
        $id = (int) $request->getRouteParam(0);
        $repo = new CondominioRepository();
        $condominio = $repo->findById($id);
        if (!$condominio) {
            $_SESSION['_flash']['error'] = 'Condomínio não encontrado.';
            return $this->redirect('/admin/condominios');
        }
        $planos = (new PlanoRepository())->findAll();
        $usuarios = (new UsuarioRepository())->findByCondominio($id);
        return $this->view('admin/condominio-edit', [
            'condominio' => $condominio,
            'planos' => $planos,
            'usuarios' => $usuarios ?? [],
        ]);
    }

    public function atualizarCondominio(Request $request): Response
    {
        $id = (int) $request->getRouteParam(0);
        $data = [
            'plano_id' => (int) $request->post('plano_id'),
            'nome' => trim($request->post('nome') ?? ''),
            'cnpj' => preg_replace('/\D/', '', $request->post('cnpj') ?? '') ?: null,
            'endereco' => trim($request->post('endereco') ?? '') ?: null,
            'cidade' => trim($request->post('cidade') ?? '') ?: null,
            'estado' => trim($request->post('estado') ?? '') ?: null,
            'cep' => preg_replace('/\D/', '', $request->post('cep') ?? '') ?: null,
            'telefone' => trim($request->post('telefone') ?? '') ?: null,
            'status' => $request->post('status') ?? 'ativo',
        ];
        $repo = new CondominioRepository();
        $repo->update($id, $data);
        $_SESSION['_flash']['success'] = 'Condomínio atualizado.';
        return $this->redirect("/admin/condominios/{$id}");
    }

    public function criarSindico(Request $request): Response
    {
        $condominioId = (int) $request->getRouteParam(0);
        $email = trim($request->post('email') ?? '');
        $nome = trim($request->post('nome') ?? '');
        $senha = $request->post('senha') ?? '';
        if (!$email || !$nome || !$senha || strlen($senha) < 6) {
            $_SESSION['_flash']['error'] = 'Preencha nome, email e senha (mín. 6 caracteres).';
            return $this->redirect("/admin/condominios/{$condominioId}");
        }
        $usuarioRepo = new UsuarioRepository();
        if ($usuarioRepo->findByEmail($email)) {
            $_SESSION['_flash']['error'] = 'Este email já está em uso.';
            return $this->redirect("/admin/condominios/{$condominioId}");
        }
        $usuarioRepo->create([
            'condominio_id' => $condominioId,
            'email' => $email,
            'nome' => $nome,
            'senha' => $senha,
            'role' => 'sindico',
        ]);
        $_SESSION['_flash']['success'] = 'Síndico cadastrado com sucesso.';
        return $this->redirect("/admin/condominios/{$condominioId}");
    }

    public function planos(Request $request): Response
    {
        $planos = (new PlanoRepository())->findAll();
        return $this->view('admin/planos', ['planos' => $planos]);
    }
}
