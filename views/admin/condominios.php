<?php
$title = 'Admin - Condomínios';
ob_start();
?>
<h1 class="mb-4"><i class="bi bi-building"></i> Condomínios</h1>
<a href="/admin/condominios/criar" class="btn btn-primary mb-3"><i class="bi bi-plus"></i> Novo Condomínio</a>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Plano</th>
                <th>Status</th>
                <th>Pagamento</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($condominios as $c): ?>
            <tr>
                <td><?= e($c['nome'] ?? '') ?></td>
                <td><?= e($c['plano_nome'] ?? '-') ?></td>
                <td><span class="badge bg-<?= ($c['status'] ?? '') === 'ativo' ? 'success' : 'secondary' ?>"><?= e($c['status'] ?? '') ?></span></td>
                <td><span class="badge bg-<?= in_array($c['pagamento_status'] ?? '', ['vencido','bloqueado']) ? 'danger' : 'success' ?>"><?= e($c['pagamento_status'] ?? '') ?></span></td>
                <td>
                    <a href="/admin/condominios/<?= $c['id'] ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php
$content = ob_get_clean();
require base_path('views/layouts/main.php');
