<?php
$title = 'Admin - Novo Condomínio';
ob_start();
?>
<h1 class="mb-4"><i class="bi bi-building-add"></i> Novo Condomínio</h1>
<form method="POST" action="/admin/condominios">
    <?= csrf_field() ?>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Plano</label>
            <select name="plano_id" class="form-select" required>
                <?php foreach ($planos as $p): ?>
                <option value="<?= $p['id'] ?>"><?= e($p['nome']) ?> - R$ <?= number_format($p['valor'], 2, ',', '.') ?>/mês</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Nome *</label>
            <input type="text" name="nome" class="form-control" required>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">CNPJ</label>
            <input type="text" name="cnpj" class="form-control" placeholder="00.000.000/0000-00">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Telefone</label>
            <input type="text" name="telefone" class="form-control">
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Endereço</label>
        <input type="text" name="endereco" class="form-control">
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Cidade</label>
            <input type="text" name="cidade" class="form-control">
        </div>
        <div class="col-md-2 mb-3">
            <label class="form-label">Estado</label>
            <input type="text" name="estado" class="form-control" maxlength="2">
        </div>
        <div class="col-md-2 mb-3">
            <label class="form-label">CEP</label>
            <input type="text" name="cep" class="form-control">
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Criar Condomínio</button>
    <a href="/admin/condominios" class="btn btn-secondary">Cancelar</a>
</form>
<?php
$content = ob_get_clean();
require base_path('views/layouts/main.php');
