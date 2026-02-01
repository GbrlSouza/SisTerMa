<?php

declare(strict_types=1);

namespace Core;

use Core\Http\Request;
use Core\Http\Response;

class Router
{
    protected array $routes = [];
    protected array $middleware = [];

    public function get(string $path, callable|array $handler, array $middleware = []): void
    {
        $this->addRoute('GET', $path, $handler, $middleware);
    }

    public function post(string $path, callable|array $handler, array $middleware = []): void
    {
        $this->addRoute('POST', $path, $handler, $middleware);
    }

    protected function addRoute(string $method, string $path, callable|array $handler, array $middleware): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }

    public function dispatch(Request $request): Response
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $request->getMethod()) {
                continue;
            }
            $params = $this->matchPath($route['path'], $request->getPath());
            if ($params !== null) {
                $request->setRouteParams($params);
                $handler = $route['handler'];
                $middleware = $route['middleware'] ?? [];
                return $this->runMiddlewareAndHandler($request, $handler, $middleware);
            }
        }
        return new Response('Página não encontrada', 404);
    }

    protected function matchPath(string $pattern, string $path): ?array
    {
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '([^/]+)', $pattern);
        $pattern = '#^' . $pattern . '$#';
        if (preg_match($pattern, $path, $matches)) {
            array_shift($matches);
            return $matches;
        }
        return null;
    }

    protected function runMiddlewareAndHandler(Request $request, callable|array $handler, array $middleware): Response
    {
        $next = function (Request $req) use ($handler) {
            return $this->invokeHandler($req, $handler);
        };
        foreach (array_reverse($middleware) as $mw) {
            $next = fn(Request $req) => (new $mw())->handle($req, $next);
        }
        return $next($request);
    }

    protected function invokeHandler(Request $request, callable|array $handler): Response
    {
        if (is_array($handler)) {
            [$class, $method] = $handler;
            $controller = new $class();
            $result = $controller->$method($request);
        } else {
            $result = $handler($request);
        }
        return $result instanceof Response ? $result : new Response((string) $result);
    }
}
