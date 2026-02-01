<?php
$title = 'Síndico - Avisos';
ob_start();
?>
<h1 class="mb-4"><i class="bi bi-megaphone"></i> Avisos</h1>
<form method="POST" action="/sindico/avisos" class="card mb-4">
    <div class="card-body">
        <?= csrf_field() ?>
        <h6>Publicar Aviso</h6>
        <div class="mb-2">
            <input type="text" name="titulo" class="form-control" placeholder="Título *" required>
        </div>
        <div class="mb-2">
            <textarea name="conteudo" class="form-control" rows="3" placeholder="Conteúdo *" required></textarea>
        </div>
        <div class="d-flex gap-2">
            <select name="tipo" class="form-select form-select-sm" style="width: auto;">
                <option value="geral">Geral</option>
                <option value="urgente">Urgente</option>
                <option value="manutencao">Manutenção</option>
                <option value="reuniao">Reunião</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Publicar</button>
        </div>
    </div>
</form>
<?php if (empty($avisos)): ?>
<p class="text-muted">Nenhum aviso cadastrado.</p>
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
