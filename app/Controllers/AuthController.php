<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Http\Request;
use Core\Http\Response;
use App\Services\AuthService;

class AuthController extends BaseController
{
    public function loginForm(Request $request): Response
    {
        return $this->view('auth/login');
    }

    public function login(Request $request): Response
    {
        $email = trim($request->post('email') ?? '');
        $senha = $request->post('senha') ?? '';
        if (!$email || !$senha) {
            $_SESSION['_flash']['error'] = 'Preencha email e senha.';
            $_SESSION['_old'] = ['email' => $email];
            return $this->redirect('/login');
        }
        $auth = new AuthService();
        $user = $auth->login($email, $senha);
        if (!$user) {
            $_SESSION['_flash']['error'] = 'Email ou senha inválidos.';
            $_SESSION['_old'] = ['email' => $email];
            return $this->redirect('/login');
        }
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_role'] = $user['user_role'];
        $_SESSION['user_name'] = $user['user_name'];
        $_SESSION['user_email'] = $user['user_email'] ?? null;
        $_SESSION['condominio_id'] = $user['condominio_id'] ?? null;
        $_SESSION['morador_id'] = $user['morador_id'] ?? null;
        $redirect = match ($user['user_role']) {
            'admin_master' => '/admin',
            'sindico' => '/sindico',
            default => '/morador',
        };
        return $this->redirect($redirect);
    }

    public function logout(Request $request): Response
    {
        session_destroy();
        return $this->redirect('/login');
    }
}
