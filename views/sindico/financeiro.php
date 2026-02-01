<?php
$title = 'Síndico - Financeiro';
ob_start();
?>
<h1 class="mb-4"><i class="bi bi-cash-stack"></i> Financeiro</h1>
<div class="card mb-4">
    <div class="card-body">
        <h5><?= e($condominio->nome) ?></h5>
        <p class="mb-0">Status do pagamento: <span class="badge bg-<?= in_array($condominio->pagamentoStatus, ['vencido','bloqueado']) ? 'danger' : 'success' ?>"><?= e($condominio->pagamentoStatus) ?></span></p>
    </div>
</div>
<h5>Histórico de Pagamentos</h5>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Vencimento</th>
                <th>Valor</th>
                <th>Status</th>
                <th>Pago em</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pagamentos as $p): ?>
            <tr>
                <td><?= date('d/m/Y', strtotime($p['vencimento'])) ?></td>
                <td>R$ <?= number_format($p['valor'], 2, ',', '.') ?></td>
                <td><span class="badge bg-<?= $p['status'] === 'pago' ? 'success' : ($p['status'] === 'vencido' ? 'danger' : 'warning') ?>"><?= e($p['status']) ?></span></td>
                <td><?= $p['pago_em'] ? date('d/m/Y H:i', strtotime($p['pago_em'])) : '-' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<a href="/sindico/pagamento" class="btn btn-primary"><i class="bi bi-qr-code"></i> Gerar Pagamento PIX</a>
<?php
$content = ob_get_clean();
require base_path('views/layouts/main.php');
