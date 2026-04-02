<?php

session_start();

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../Core/Router.php';
require_once __DIR__ . '/../Controllers/MedicoController.php';
require_once __DIR__ . '/../Controllers/AgendamentoController.php';
require_once __DIR__ . '/../Controllers/AdminController.php';
require_once __DIR__ . '/../Controllers/AuthController.php';
require_once __DIR__ . '/../Controllers/ApiController.php';

use Core\Router;
use Controllers\MedicoController;
use Controllers\AgendamentoController;
use Controllers\AdminController;
use Controllers\AuthController;
use Controllers\ApiController;

$router = new Router();

// ── Rotas públicas ────────────────────────────────────────────────────────────
$router->add('GET', 'listar', function () {
    (new MedicoController())->listarDisponibilidade();
});

$router->add('GET', 'api_disponibilidade', function () {
    (new ApiController())->getDisponibilidade();
});

$router->add('POST', 'agendar', function () {
    (new AgendamentoController())->salvar();
});

$router->add('GET', 'login', function () {
    (new AuthController())->login();
});

$router->add('POST', 'logar', function () {
    (new AuthController())->logar();
});

$router->add('GET', 'logout', function () {
    (new AuthController())->logout();
});

// ── Rotas protegidas (requerem autenticação) ──────────────────────────────────
$router->add('GET', 'painel', function () {
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: index.php?acao=login');
        exit;
    }
    (new AdminController())->painel();
});

$router->add('GET', 'atualizar_status', function () {
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: index.php?acao=login');
        exit;
    }
    (new AdminController())->alterarStatus();
});

// ── Despacha a requisição ─────────────────────────────────────────────────────
$router->dispatch();
