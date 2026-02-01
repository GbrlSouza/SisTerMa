<?php
$title = 'Síndico - Pagamento PIX';
ob_start();
?>
<h1 class="mb-4"><i class="bi bi-qr-code"></i> Pagamento PIX</h1>
<div class="card mb-4">
    <div class="card-body">
        <h5><?= e($condominio->nome ?? '') ?></h5>
        <p>Plano: <?= e($plano['nome'] ?? '-') ?> - R$ <?= number_format($plano['valor'] ?? 0, 2, ',', '.') ?>/mês</p>
        <p class="mb-0">Status: <span class="badge bg-<?= in_array($condominio->pagamentoStatus ?? '', ['vencido','bloqueado']) ? 'danger' : 'success' ?>"><?= e($condominio->pagamentoStatus ?? '') ?></span></p>
    </div>
</div>
<?php if ($pagamentoPendente): ?>
<div class="card border-primary">
    <div class="card-body text-center">
        <h5>Escaneie o QR Code para pagar</h5>
        <?php if (!empty($pagamentoPendente['qr_code'])): ?>
        <div class="my-4">
            <?php $qr = $pagamentoPendente['qr_code']; $src = str_starts_with($qr, 'data:') ? $qr : "data:image/png;base64,{$qr}"; ?>
            <img src="<?= e($src) ?>" alt="QR Code PIX" style="max-width: 250px;">
        </div>
        <?php else: ?>
        <p class="text-muted">QR Code em processamento. Tente gerar novamente.</p>
        <?php endif; ?>
        <p class="mb-0">Vencimento: <?= date('d/m/Y', strtotime($pagamentoPendente['vencimento'])) ?></p>
        <p>Valor: R$ <?= number_format($pagamentoPendente['valor'], 2, ',', '.') ?></p>
    </div>
</div>
<?php endif; ?>
<form method="POST" action="/sindico/pagamento/gerar" class="mt-3">
    <?= csrf_field() ?>
    <button type="submit" class="btn btn-primary" <?= $pagamentoPendente ? 'disabled' : '' ?>>
        <i class="bi bi-qr-code-scan"></i> <?= $pagamentoPendente ? 'Pagamento já gerado' : 'Gerar Cobrança PIX' ?>
    </button>
</form>
<?php
$content = ob_get_clean();
require base_path('views/layouts/main.php');
