<?php

namespace Core;

class Router
{
    private array $routes = [];

    public function add(string $method, string $action, callable $handler): void
    {
        $this->routes[strtoupper($method)][$action] = $handler;
    }

    public function dispatch(): void
    {
        $action = $_GET['acao'] ?? 'listar';
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$method][$action])) {
            call_user_func($this->routes[$method][$action]);
            return;
        }

        http_response_code(404);
        echo 'Página não encontrada.';
    }
}
