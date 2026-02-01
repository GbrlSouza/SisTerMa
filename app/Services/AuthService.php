<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Usuario;
use App\Repositories\UsuarioRepository;
use App\Repositories\MoradorRepository;

class AuthService
{
    public function __construct(
        protected UsuarioRepository $usuarioRepo = new UsuarioRepository(),
        protected MoradorRepository $moradorRepo = new MoradorRepository()
    ) {}

    public function login(string $email, string $senha): ?array
    {
        $usuario = $this->usuarioRepo->findByEmail($email);
        if (!$usuario || !password_verify($senha, $usuario->senha ?? '')) {
            return null;
        }
        $usuarioData = $this->getUsuarioData($usuario);
        if (!$usuarioData) {
            return null;
        }
        $this->usuarioRepo->updateUltimoAcesso($usuario->id);
        return $usuarioData;
    }

    protected function getUsuarioData(Usuario $usuario): ?array
    {
        if ($usuario->role === 'admin_master') {
            return [
                'user_id' => $usuario->id,
                'user_role' => 'admin_master',
                'user_name' => $usuario->nome,
                'user_email' => $usuario->email,
                'condominio_id' => null,
            ];
        }
        if ($usuario->role === 'sindico') {
            return [
                'user_id' => $usuario->id,
                'user_role' => 'sindico',
                'user_name' => $usuario->nome,
                'user_email' => $usuario->email,
                'condominio_id' => $usuario->condominioId,
            ];
        }
        if ($usuario->role === 'morador') {
            $morador = $this->moradorRepo->findByUsuario($usuario->id);
            if ($morador) {
                return [
                    'user_id' => $usuario->id,
                    'user_role' => 'morador',
                    'user_name' => $morador->nome,
                    'user_email' => $morador->email ?? $usuario->email,
                    'condominio_id' => $morador->condominioId,
                    'morador_id' => $morador->id,
                ];
            }
        }
        return null;
    }
}
