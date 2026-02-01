<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\SindicoController;
use App\Controllers\MoradorController;
use App\Controllers\WebhookController;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\GuestMiddleware;
use App\Middlewares\AdminMiddleware;
use App\Middlewares\SindicoMiddleware;
use App\Middlewares\MoradorMiddleware;
use App\Middlewares\TenantMiddleware;
use App\Middlewares\CsrfMiddleware;

$app->get('/', [AuthController::class, 'loginForm'], [GuestMiddleware::class]);
$app->get('/login', [AuthController::class, 'loginForm'], [GuestMiddleware::class]);
$app->post('/login', [AuthController::class, 'login'], [GuestMiddleware::class, CsrfMiddleware::class]);
$app->get('/logout', [AuthController::class, 'logout']);

$app->post('/webhook/picpay', [WebhookController::class, 'picpay']);

$app->get('/admin', [AdminController::class, 'dashboard'], [AuthMiddleware::class, AdminMiddleware::class]);
$app->get('/admin/condominios', [AdminController::class, 'condominios'], [AuthMiddleware::class, AdminMiddleware::class]);
$app->get('/admin/condominios/criar', [AdminController::class, 'condominioForm'], [AuthMiddleware::class, AdminMiddleware::class]);
$app->post('/admin/condominios', [AdminController::class, 'criarCondominio'], [AuthMiddleware::class, AdminMiddleware::class, CsrfMiddleware::class]);
$app->get('/admin/condominios/{id}', [AdminController::class, 'editarCondominio'], [AuthMiddleware::class, AdminMiddleware::class]);
$app->post('/admin/condominios/{id}', [AdminController::class, 'atualizarCondominio'], [AuthMiddleware::class, AdminMiddleware::class, CsrfMiddleware::class]);
$app->post('/admin/condominios/{id}/sindico', [AdminController::class, 'criarSindico'], [AuthMiddleware::class, AdminMiddleware::class, CsrfMiddleware::class]);
$app->get('/admin/planos', [AdminController::class, 'planos'], [AuthMiddleware::class, AdminMiddleware::class]);

$app->get('/sindico', [SindicoController::class, 'dashboard'], [AuthMiddleware::class, SindicoMiddleware::class, TenantMiddleware::class]);
$app->get('/sindico/moradores', [SindicoController::class, 'moradores'], [AuthMiddleware::class, SindicoMiddleware::class, TenantMiddleware::class]);
$app->post('/sindico/moradores', [SindicoController::class, 'criarMorador'], [AuthMiddleware::class, SindicoMiddleware::class, TenantMiddleware::class, CsrfMiddleware::class]);
$app->get('/sindico/avisos', [SindicoController::class, 'avisos'], [AuthMiddleware::class, SindicoMiddleware::class, TenantMiddleware::class]);
$app->post('/sindico/avisos', [SindicoController::class, 'criarAviso'], [AuthMiddleware::class, SindicoMiddleware::class, TenantMiddleware::class, CsrfMiddleware::class]);
$app->get('/sindico/reservas', [SindicoController::class, 'reservas'], [AuthMiddleware::class, SindicoMiddleware::class, TenantMiddleware::class]);
$app->get('/sindico/financeiro', [SindicoController::class, 'financeiro'], [AuthMiddleware::class, SindicoMiddleware::class, TenantMiddleware::class]);
$app->get('/sindico/pagamento', [SindicoController::class, 'pagamento'], [AuthMiddleware::class, SindicoMiddleware::class]);
$app->post('/sindico/pagamento/gerar', [SindicoController::class, 'gerarPagamento'], [AuthMiddleware::class, SindicoMiddleware::class, CsrfMiddleware::class]);

$app->get('/morador', [MoradorController::class, 'dashboard'], [AuthMiddleware::class, MoradorMiddleware::class, TenantMiddleware::class]);
$app->get('/morador/avisos', [MoradorController::class, 'avisos'], [AuthMiddleware::class, MoradorMiddleware::class, TenantMiddleware::class]);
$app->get('/morador/reservas', [MoradorController::class, 'reservas'], [AuthMiddleware::class, MoradorMiddleware::class, TenantMiddleware::class]);
$app->get('/morador/boletos', [MoradorController::class, 'boletos'], [AuthMiddleware::class, MoradorMiddleware::class, TenantMiddleware::class]);
$app->get('/morador/ocorrencias', [MoradorController::class, 'ocorrencias'], [AuthMiddleware::class, MoradorMiddleware::class, TenantMiddleware::class]);
$app->post('/morador/ocorrencias', [MoradorController::class, 'criarOcorrencia'], [AuthMiddleware::class, MoradorMiddleware::class, TenantMiddleware::class, CsrfMiddleware::class]);
