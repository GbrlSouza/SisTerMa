<?php
$title = 'Morador - Ocorrências';
ob_start();
?>
<h1 class="mb-4"><i class="bi bi-exclamation-triangle"></i> Ocorrências</h1>
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalOcorrencia">
    <i class="bi bi-plus"></i> Nova Ocorrência
</button>
<?php if (empty($ocorrencias)): ?>
<p class="text-muted">Nenhuma ocorrência registrada.</p>
<?php else: ?>
<div class="list-group">
    <?php foreach ($ocorrencias as $o): ?>
    <div class="list-group-item">
        <h6><?= e($o['titulo']) ?> <span class="badge bg-<?= $o['status'] === 'resolvida' ? 'success' : 'warning' ?>"><?= e($o['status']) ?></span></h6>
        <p class="mb-1"><?= nl2br(e($o['descricao'])) ?></p>
        <small class="text-muted"><?= e($o['tipo']) ?> - <?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></small>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="modal fade" id="modalOcorrencia" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/morador/ocorrencias">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Nova Ocorrência</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Título *</label>
                        <input type="text" name="titulo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrição *</label>
                        <textarea name="descricao" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo</label>
                        <select name="tipo" class="form-select">
                            <option value="reclamacao">Reclamação</option>
                            <option value="sugestao">Sugestão</option>
                            <option value="elogio">Elogio</option>
                            <option value="manutencao">Manutenção</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require base_path('views/layouts/main.php');
