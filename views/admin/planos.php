<?php
$title = 'Admin - Planos';
ob_start();
?>
<h1 class="mb-4"><i class="bi bi-credit-card"></i> Planos</h1>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Valor</th>
                <th>Tipo</th>
                <th>Máx. Unidades</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($planos as $p): ?>
            <tr>
                <td><?= e($p['nome']) ?></td>
                <td>R$ <?= number_format($p['valor'], 2, ',', '.') ?></td>
                <td><?= e($p['tipo']) ?></td>
                <td><?= $p['max_unidades'] ?: 'Ilimitado' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php
$content = ob_get_clean();
require base_path('views/layouts/main.php');
