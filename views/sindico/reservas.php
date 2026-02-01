<?php
$title = 'Síndico - Reservas';
ob_start();
?>
<h1 class="mb-4"><i class="bi bi-calendar-check"></i> Reservas</h1>
<?php if (empty($reservas)): ?>
<p class="text-muted">Nenhuma reserva registrada.</p>
<?php else: ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Área</th>
                <th>Morador</th>
                <th>Data</th>
                <th>Horário</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservas as $r): ?>
            <tr>
                <td><?= e($r['area_nome'] ?? '-') ?></td>
                <td><?= e($r['morador_nome'] ?? '-') ?></td>
                <td><?= date('d/m/Y', strtotime($r['data_reserva'])) ?></td>
                <td><?= date('H:i', strtotime($r['hora_inicio'])) ?> - <?= date('H:i', strtotime($r['hora_fim'])) ?></td>
                <td><span class="badge bg-<?= $r['status'] === 'confirmada' ? 'success' : 'secondary' ?>"><?= e($r['status']) ?></span></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
<?php
$content = ob_get_clean();
require base_path('views/layouts/main.php');
