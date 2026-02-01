<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\PagamentoRepository;
use App\Repositories\CondominioRepository;
use Core\Http\Response;

class PicPayService
{
    protected string $baseUrl;
    protected ?string $token = null;

    public function __construct(
        protected PagamentoRepository $pagamentoRepo = new PagamentoRepository(),
        protected CondominioRepository $condominioRepo = new CondominioRepository()
    ) {
        $config = config('picpay');
        $this->baseUrl = $config['sandbox']
            ? 'https://api-sandbox.picpay.com'
            : 'https://api.picpay.com';
    }

    public function gerarCobrancaPix(
        string $referenceId,
        float $valor,
        string $descricao,
        string $nomeCliente,
        string $emailCliente,
        ?string $cpfCliente = null
    ): array {
        $valorCentavos = (int) round($valor * 100);
        $payload = [
            'referenceId' => $referenceId,
            'callbackUrl' => config('app.url') . '/webhook/picpay',
            'returnUrl' => config('app.url') . '/sindico/pagamento',
            'value' => $valorCentavos,
            'expiresAt' => date('c', strtotime('+30 minutes')),
            'buyer' => [
                'firstName' => $this->truncarNome($nomeCliente),
                'lastName' => '',
                'document' => $cpfCliente ? preg_replace('/\D/', '', $cpfCliente) : '00000000000',
                'documentType' => 'CPF',
                'email' => $emailCliente,
            ],
        ];

        $response = $this->request('POST', '/ecommerce/public/payments', $payload);
        return $response;
    }

    public function processarWebhook(array $payload): Response
    {
        $webhookToken = config('picpay.webhook_token');
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if ($webhookToken && $authHeader !== $webhookToken) {
            return new Response(json_encode(['error' => 'Unauthorized']), 401, ['Content-Type' => 'application/json']);
        }

        $eventType = $_SERVER['HTTP_EVENT_TYPE'] ?? '';
        if ($eventType !== 'TransactionUpdateMessage') {
            return new Response(json_encode(['received' => true]), 200, ['Content-Type' => 'application/json']);
        }

        $status = $payload['data']['status'] ?? null;
        $merchantChargeId = $payload['data']['merchantChargeId'] ?? null;

        if ($status === 'PAID' && $merchantChargeId) {
            $pagamento = $this->pagamentoRepo->findByReferenceId($merchantChargeId);
            if ($pagamento) {
                $this->pagamentoRepo->marcarComoPago((int) $pagamento['id']);
                $vencimento = date('Y-m-d', strtotime('+1 month'));
                $this->condominioRepo->updatePagamentoStatus(
                    (int) $pagamento['condominio_id'],
                    'pago',
                    $vencimento
                );
            }
        }

        return new Response(json_encode(['received' => true]), 200, ['Content-Type' => 'application/json']);
    }

    protected function request(string $method, string $path, array $body = []): array
    {
        $token = $this->getToken();
        $url = $this->baseUrl . $path;
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
            ],
        ]);
        if (!empty($body)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $data = json_decode((string) $response, true) ?? [];
        $data['_http_code'] = $httpCode;
        return $data;
    }

    protected function getToken(): string
    {
        if ($this->token) {
            return $this->token;
        }
        $clientId = config('picpay.client_id');
        $clientSecret = config('picpay.client_secret');
        if (!$clientId || !$clientSecret) {
            return 'sandbox-token';
        }
        $ch = curl_init($this->baseUrl . '/oauth2/token');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode([
                'grant_type' => 'client_credentials',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
            ]),
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode((string) $response, true);
        $this->token = $data['access_token'] ?? 'sandbox-token';
        return $this->token;
    }

    protected function truncarNome(string $nome): string
    {
        $partes = explode(' ', $nome, 2);
        return $partes[0] ?? 'Cliente';
    }
}
