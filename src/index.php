<?php

// Importamos os nossos três Controllers
require_once __DIR__ . '/Controllers/MedicoController.php';
require_once __DIR__ . '/Controllers/AgendamentoController.php';
require_once __DIR__ . '/Controllers/AdminController.php';

use Controllers\MedicoController;
use Controllers\AgendamentoController;
use Controllers\AdminController;

// O Gerente da Porta
$acao = isset($_GET['acao']) ? $_GET['acao'] : 'listar';

if ($acao === 'agendar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Rota: Salvar agendamento
    $controller = new AgendamentoController();
    $controller->salvar();

} elseif ($acao === 'painel') {
    // NOVA ROTA: Acessar o Dashboard da Clínica
    $controller = new AdminController();
    $controller->painel();

} else {
    // Rota Padrão: Mostrar a tela bonita para o paciente
    $controller = new MedicoController();
    $controller->listarDisponibilidade();
}

?>