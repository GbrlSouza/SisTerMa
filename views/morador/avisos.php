<?php
$title = 'Morador - Avisos';
ob_start();
?>
<h1 class="mb-4"><i class="bi bi-megaphone"></i> Avisos</h1>
<?php if (empty($avisos)): ?>
<p class="text-muted">Nenhum aviso no momento.</p>
<?php else: ?>
<div class="list-group">
    <?php foreach ($avisos as $a): ?>
    <div class="list-group-item">
        <h5><?= e($a['titulo']) ?></h5>
        <p class="mb-1"><?= nl2br(e($a['conteudo'])) ?></p>
        <small class="text-muted">Por <?= e($a['autor'] ?? '-') ?> em <?= date('d/m/Y H:i', strtotime($a['created_at'])) ?></small>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<?php
$content = ob_get_clean();
require base_path('views/layouts/main.php');
