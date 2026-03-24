<?php
// OBRIGATÓRIO: Liga o motor de Sessões do PHP (Tem que ser a primeira linha!)
session_start();

require_once __DIR__ . '/Controllers/MedicoController.php';
require_once __DIR__ . '/Controllers/AgendamentoController.php';
require_once __DIR__ . '/Controllers/AdminController.php';
require_once __DIR__ . '/Controllers/AuthController.php'; // Adicionamos o Auth

use Controllers\MedicoController;
use Controllers\AgendamentoController;
use Controllers\AdminController;
use Controllers\AuthController;

$acao = isset($_GET['acao']) ? $_GET['acao'] : 'listar';

// ROTAS PÚBLICAS (Qualquer um acessa)
if ($acao === 'listar') {
    $controller = new MedicoController();
    $controller->listarDisponibilidade();

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

// ROTA PRIVADA (Apenas Logados)
} elseif ($acao === 'painel') {
    // O GUARDA: Se não tiver o id na sessão, expulsa para o login!
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: index.php?acao=login");
        exit;
    }
    
    $controller = new AdminController();
    $controller->painel();
// ROTA PRIVADA (Apenas Logados)
} elseif ($acao === 'painel') {
    // O GUARDA: Se não tiver o id na sessão, expulsa para o login!
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: index.php?acao=login");
        exit;
    }
    
    $controller = new AdminController();
    $controller->painel();

// NOVA ROTA: Atualizar status do agendamento
} elseif ($acao === 'atualizar_status') {
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: index.php?acao=login");
        exit;
    }
    
    $controller = new AdminController();
    $controller->alterarStatus();
}
?>
