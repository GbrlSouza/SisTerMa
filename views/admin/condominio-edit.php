<?php
$title = 'Admin - Editar Condomínio';
$c = $condominio;
ob_start();
?>
<h1 class="mb-4"><i class="bi bi-pencil"></i> Editar Condomínio</h1>
<form method="POST" action="/admin/condominios/<?= $c->id ?>">
    <?= csrf_field() ?>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Plano</label>
            <select name="plano_id" class="form-select" required>
                <?php foreach ($planos as $p): ?>
                <option value="<?= $p['id'] ?>" <?= $c->planoId == $p['id'] ? 'selected' : '' ?>><?= e($p['nome']) ?> - R$ <?= number_format($p['valor'], 2, ',', '.') ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Nome *</label>
            <input type="text" name="nome" class="form-control" value="<?= e($c->nome) ?>" required>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">CNPJ</label>
            <input type="text" name="cnpj" class="form-control" value="<?= e($c->cnpj) ?>">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="ativo" <?= $c->status === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                <option value="bloqueado" <?= $c->status === 'bloqueado' ? 'selected' : '' ?>>Bloqueado</option>
                <option value="suspenso" <?= $c->status === 'suspenso' ? 'selected' : '' ?>>Suspenso</option>
            </select>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Endereço</label>
        <input type="text" name="endereco" class="form-control" value="<?= e($c->endereco) ?>">
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Cidade</label>
            <input type="text" name="cidade" class="form-control" value="<?= e($c->cidade) ?>">
        </div>
        <div class="col-md-2 mb-3">
            <label class="form-label">Estado</label>
            <input type="text" name="estado" class="form-control" value="<?= e($c->estado) ?>" maxlength="2">
        </div>
        <div class="col-md-2 mb-3">
            <label class="form-label">CEP</label>
            <input type="text" name="cep" class="form-control" value="<?= e($c->cep) ?>">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Telefone</label>
            <input type="text" name="telefone" class="form-control" value="<?= e($c->telefone) ?>">
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="/admin/condominios" class="btn btn-secondary">Voltar</a>
</form>
<hr class="my-4">
<h5>Síndicos</h5>
<?php if (!empty($usuarios)): ?>
<table class="table">
    <?php foreach ($usuarios as $u): ?>
    <tr><td><?= e($u->nome) ?></td><td><?= e($u->email) ?></td></tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>
<h6 class="mt-3">Cadastrar Síndico</h6>
<form method="POST" action="/admin/condominios/<?= $c->id ?>/sindico" class="row g-2">
    <?= csrf_field() ?>
    <div class="col-md-3"><input type="text" name="nome" class="form-control form-control-sm" placeholder="Nome" required></div>
    <div class="col-md-3"><input type="email" name="email" class="form-control form-control-sm" placeholder="E-mail" required></div>
    <div class="col-md-3"><input type="password" name="senha" class="form-control form-control-sm" placeholder="Senha (mín. 6)" required minlength="6"></div>
    <div class="col-md-2"><button type="submit" class="btn btn-sm btn-primary">Cadastrar</button></div>
</form>
<?php
$content = ob_get_clean();
require base_path('views/layouts/main.php');
