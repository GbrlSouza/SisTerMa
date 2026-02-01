<?php
$title = 'Login';
ob_start();
?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-body p-5">
                <h2 class="card-title text-center mb-4">
                    <i class="bi bi-building text-primary"></i> SisTerMa
                </h2>
                <p class="text-center text-muted mb-4">Sistema de Gestão de Condomínios</p>
                <form method="POST" action="/login">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">E-mail</label>
                        <input type="email" name="email" class="form-control" value="<?= e(old('email')) ?>" required autofocus>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Senha</label>
                        <input type="password" name="senha" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                </form>
                <p class="text-center text-muted mt-4 small">
                    Admin: admin@sisterma.com.br / admin123
                </p>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require base_path('views/layouts/main.php');
