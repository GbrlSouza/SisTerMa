<?php
$title = 'Morador - Início';
ob_start();
?>
<h1 class="mb-4"><i class="bi bi-house-door"></i> Olá, <?= e($morador->nome ?? 'Morador') ?>!</h1>
<h5 class="mb-3"><i class="bi bi-megaphone"></i> Últimos Avisos</h5>
<?php if (empty($avisos)): ?>
<p class="text-muted">Nenhum aviso no momento.</p>
<?php else: ?>
<div class="list-group">
    <?php foreach ($avisos as $a): ?>
    <div class="list-group-item">
        <h6><?= e($a['titulo']) ?></h6>
        <p class="mb-1 small"><?= nl2br(e(mb_substr($a['conteudo'], 0, 150))) ?><?= mb_strlen($a['conteudo']) > 150 ? '...' : '' ?></p>
        <small class="text-muted"><?= date('d/m/Y', strtotime($a['created_at'])) ?></small>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<div class="mt-4">
    <a href="/morador/avisos" class="btn btn-outline-primary me-2"><i class="bi bi-megaphone"></i> Ver todos os avisos</a>
    <a href="/morador/reservas" class="btn btn-outline-primary me-2"><i class="bi bi-calendar-check"></i> Reservas</a>
    <a href="/morador/boletos" class="btn btn-outline-primary me-2"><i class="bi bi-upc-scan"></i> Boletos</a>
    <a href="/morador/ocorrencias" class="btn btn-outline-primary"><i class="bi bi-exclamation-triangle"></i> Ocorrências</a>
</div>
<?php
$content = ob_get_clean();
require base_path('views/layouts/main.php');
