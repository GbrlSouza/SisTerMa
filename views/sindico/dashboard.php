<?php
$title = 'Síndico - Dashboard';
ob_start();
?>
<h1 class="mb-4"><i class="bi bi-speedometer2"></i> Dashboard</h1>
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card border-primary">
            <div class="card-body">
                <h5 class="card-title text-primary"><i class="bi bi-building"></i> <?= e($condominio->nome) ?></h5>
                <p class="mb-0">Status: <span class="badge bg-<?= $condominio->pagamentoStatus === 'pago' ? 'success' : 'warning' ?>"><?= e($condominio->pagamentoStatus) ?></span></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="mb-0"><?= $totalMoradores ?></h3>
                <small class="text-muted">Moradores</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="mb-0"><?= $totalAvisos ?></h3>
                <small class="text-muted">Avisos</small>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <h5><i class="bi bi-lightning"></i> Ações Rápidas</h5>
        <a href="/sindico/moradores" class="btn btn-outline-primary me-2"><i class="bi bi-people"></i> Moradores</a>
        <a href="/sindico/avisos" class="btn btn-outline-primary me-2"><i class="bi bi-megaphone"></i> Avisos</a>
        <a href="/sindico/reservas" class="btn btn-outline-primary me-2"><i class="bi bi-calendar-check"></i> Reservas</a>
        <a href="/sindico/pagamento" class="btn btn-outline-success"><i class="bi bi-qr-code"></i> Pagamento</a>
    </div>
</div>
<?php
$content = ob_get_clean();
require base_path('views/layouts/main.php');
