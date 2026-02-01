<?php
$title = 'Admin - Dashboard';
ob_start();
?>
<h1 class="mb-4"><i class="bi bi-speedometer2"></i> Dashboard</h1>
<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-primary">
            <div class="card-body">
                <h5 class="card-title text-primary"><i class="bi bi-building"></i> Total de Condomínios</h5>
                <p class="display-4 mb-0"><?= $totalCondominios ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-danger">
            <div class="card-body">
                <h5 class="card-title text-danger"><i class="bi bi-exclamation-triangle"></i> Inadimplentes</h5>
                <p class="display-4 mb-0"><?= $inadimplentes ?></p>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require base_path('views/layouts/main.php');
