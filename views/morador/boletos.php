<?php
$title = 'Morador - Boletos';
ob_start();
?>
<h1 class="mb-4"><i class="bi bi-upc-scan"></i> Meus Boletos</h1>
<?php if (empty($boletos)): ?>
<p class="text-muted">Nenhum boleto registrado.</p>
<?php else: ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Descrição</th>
                <th>Vencimento</th>
                <th>Valor</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($boletos as $b): ?>
            <tr>
                <td><?= e($b['descricao']) ?></td>
                <td><?= date('d/m/Y', strtotime($b['vencimento'])) ?></td>
                <td>R$ <?= number_format($b['valor'], 2, ',', '.') ?></td>
                <td><span class="badge bg-<?= $b['status'] === 'pago' ? 'success' : ($b['status'] === 'vencido' ? 'danger' : 'warning') ?>"><?= e($b['status']) ?></span></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
<?php
$content = ob_get_clean();
require base_path('views/layouts/main.php');
