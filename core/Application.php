<?php

declare(strict_types=1);

namespace Core;

use Core\Http\Request;
use Core\Http\Response;
use Core\Router;

class Application
{
    protected Router $router;

    public function __construct()
    {
        $this->router = new Router();
    }

    public function get(string $path, callable|array $handler, array $middleware = []): self
    {
        $this->router->get($path, $handler, $middleware);
        return $this;
    }

    public function post(string $path, callable|array $handler, array $middleware = []): self
    {
        $this->router->post($path, $handler, $middleware);
        return $this;
    }

    public function run(): void
    {
        $request = Request::createFromGlobals();
        $response = $this->router->dispatch($request);
        $response->send();
    }
}
