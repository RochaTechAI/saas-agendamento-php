<?php
// OBRIGATÓRIO: Liga o motor de Sessões do PHP
session_start();

require_once __DIR__ . '/Controllers/MedicoController.php';
require_once __DIR__ . '/Controllers/AgendamentoController.php';
require_once __DIR__ . '/Controllers/AdminController.php';
require_once __DIR__ . '/Controllers/AuthController.php';
require_once __DIR__ . '/Controllers/ApiController.php'; // Adicionado o Controlador da API

use Controllers\MedicoController;
use Controllers\AgendamentoController;
use Controllers\AdminController;
use Controllers\AuthController;
use Controllers\ApiController;

$acao = isset($_GET['acao']) ? $_GET['acao'] : 'listar';

// ROTAS PÚBLICAS E API
if ($acao === 'listar') {
    $controller = new MedicoController();
    $controller->listarDisponibilidade();

// ======= A NOSSA NOVA ROTA DA API AQUI =======
} elseif ($acao === 'api_disponibilidade') {
    $controller = new ApiController();
    $controller->getDisponibilidade();
// =============================================

} elseif ($acao === 'agendar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new AgendamentoController();
    $controller->salvar();

} elseif ($acao === 'login') {
    $controller = new AuthController();
    $controller->login();

} elseif ($acao === 'logar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new AuthController();
    $controller->logar();

} elseif ($acao === 'logout') {
    $controller = new AuthController();
    $controller->logout();

// ROTAS PRIVADAS (Painel Administrativo)
} elseif ($acao === 'painel') {
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: index.php?acao=login");
        exit;
    }
    $controller = new AdminController();
    $controller->painel();

} elseif ($acao === 'atualizar_status') {
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: index.php?acao=login");
        exit;
    }
    $controller = new AdminController();
    $controller->alterarStatus();
}
?>