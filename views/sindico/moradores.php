<?php
$title = 'Síndico - Moradores';
ob_start();
?>
<h1 class="mb-4"><i class="bi bi-people"></i> Moradores</h1>
<form method="POST" action="/sindico/moradores" class="card mb-4">
    <div class="card-body">
        <?= csrf_field() ?>
        <h6>Cadastrar Morador</h6>
        <div class="row g-2">
            <div class="col-md-2"><input type="text" name="nome" class="form-control form-control-sm" placeholder="Nome *" required></div>
            <div class="col-md-2"><input type="email" name="email" class="form-control form-control-sm" placeholder="E-mail (para acesso)"></div>
            <div class="col-md-2"><input type="password" name="senha" class="form-control form-control-sm" placeholder="Senha (mín. 6, para acesso)" minlength="6"></div>
            <div class="col-md-2"><input type="text" name="cpf" class="form-control form-control-sm" placeholder="CPF"></div>
            <div class="col-md-2"><input type="text" name="telefone" class="form-control form-control-sm" placeholder="Telefone"></div>
            <div class="col-md-1"><input type="text" name="bloco" class="form-control form-control-sm" placeholder="Bloco"></div>
            <div class="col-md-1"><input type="text" name="unidade" class="form-control form-control-sm" placeholder="Unidade"></div>
            <div class="col-md-2"><button type="submit" class="btn btn-sm btn-primary">Cadastrar</button></div>
        </div>
        <small class="text-muted">Preencha e-mail e senha para liberar acesso do morador ao sistema.</small>
    </div>
</form>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Unidade</th>
                <th>E-mail</th>
                <th>Telefone</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($moradores as $m): ?>
            <tr>
                <td><?= e($m->nome) ?></td>
                <td><?= e($m->bloco ? $m->bloco . ' - ' : '') ?><?= e($m->unidade ?? '-') ?></td>
                <td><?= e($m->email ?? '-') ?></td>
                <td><?= e($m->telefone ?? '-') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php
$content = ob_get_clean();
require base_path('views/layouts/main.php');
