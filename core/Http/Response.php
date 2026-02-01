<?php

declare(strict_types=1);

namespace Core\Http;

class Response
{
    public function __construct(
        protected string $content = '',
        protected int $status = 200,
        protected array $headers = []
    ) {}

    public function send(): void
    {
        http_response_code($this->status);
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }
        echo $this->content;
    }

    public function withHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function json(array $data, int $status = 200): self
    {
        $this->content = json_encode($data);
        $this->status = $status;
        $this->headers['Content-Type'] = 'application/json';
        return $this;
    }
}
