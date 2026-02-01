<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'SisTerMa') ?> - Gestão de Condomínios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">SisTerMa</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php if (!empty($_SESSION['user_id'])): ?>
                <ul class="navbar-nav me-auto">
                    <?php if ($_SESSION['user_role'] === 'admin_master'): ?>
                    <li class="nav-item"><a class="nav-link" href="/admin"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/condominios"><i class="bi bi-building"></i> Condomínios</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/planos"><i class="bi bi-credit-card"></i> Planos</a></li>
                    <?php elseif ($_SESSION['user_role'] === 'sindico'): ?>
                    <li class="nav-item"><a class="nav-link" href="/sindico"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/sindico/moradores"><i class="bi bi-people"></i> Moradores</a></li>
                    <li class="nav-item"><a class="nav-link" href="/sindico/avisos"><i class="bi bi-megaphone"></i> Avisos</a></li>
                    <li class="nav-item"><a class="nav-link" href="/sindico/reservas"><i class="bi bi-calendar-check"></i> Reservas</a></li>
                    <li class="nav-item"><a class="nav-link" href="/sindico/financeiro"><i class="bi bi-cash-stack"></i> Financeiro</a></li>
                    <li class="nav-item"><a class="nav-link" href="/sindico/pagamento"><i class="bi bi-qr-code"></i> Pagamento</a></li>
                    <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/morador"><i class="bi bi-speedometer2"></i> Início</a></li>
                    <li class="nav-item"><a class="nav-link" href="/morador/avisos"><i class="bi bi-megaphone"></i> Avisos</a></li>
                    <li class="nav-item"><a class="nav-link" href="/morador/reservas"><i class="bi bi-calendar-check"></i> Reservas</a></li>
                    <li class="nav-item"><a class="nav-link" href="/morador/boletos"><i class="bi bi-upc-scan"></i> Boletos</a></li>
                    <li class="nav-item"><a class="nav-link" href="/morador/ocorrencias"><i class="bi bi-exclamation-triangle"></i> Ocorrências</a></li>
                    <?php endif; ?>
                </ul>
                <span class="navbar-text me-3"><?= e($_SESSION['user_name'] ?? '') ?></span>
                <a class="btn btn-outline-light btn-sm" href="/logout">Sair</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <main class="container flex-grow-1 py-4">
        <?php if ($msg = flash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= e($msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        <?php if ($msg = flash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= e($msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        <?php if ($msg = flash('warning')): ?>
        <div class="alert alert-warning alert-dismissible fade show">
            <?= e($msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        <?php if ($msg = flash('info')): ?>
        <div class="alert alert-info alert-dismissible fade show">
            <?= e($msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        <?= $content ?? '' ?>
    </main>
    <footer class="bg-light py-3 mt-auto">
        <div class="container text-center text-muted small">
            SisTerMa - Sistema de Gestão de Condomínios &copy; <?= date('Y') ?>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
