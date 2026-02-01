<?php

declare(strict_types=1);

namespace Core\Http;

class Request
{
    protected array $routeParams = [];

    public static function createFromGlobals(): self
    {
        $request = new self();
        return $request;
    }

    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public function getPath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($path, PHP_URL_PATH) ?: $path;
        return '/' . trim($path, '/') ?: '/';
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    public function post(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->post($key) ?? $this->get($key) ?? $default;
    }

    public function setRouteParams(array $params): void
    {
        $this->routeParams = $params;
    }

    public function getRouteParam(int $index, mixed $default = null): mixed
    {
        return $this->routeParams[$index] ?? $default;
    }

    public function getIp(): ?string
    {
        return $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? null;
    }

    public function getUserAgent(): ?string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? null;
    }

    public function getHeader(string $name): ?string
    {
        $key = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        return $_SERVER[$key] ?? null;
    }

    public function isPost(): bool
    {
        return $this->getMethod() === 'POST';
    }

    public function isGet(): bool
    {
        return $this->getMethod() === 'GET';
    }

    public function getJsonBody(): ?array
    {
        $body = file_get_contents('php://input');
        $decoded = json_decode($body, true);
        return is_array($decoded) ? $decoded : null;
    }
}
