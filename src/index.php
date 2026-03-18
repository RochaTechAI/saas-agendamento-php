<?php

// Importamos os nossos dois Controllers
require_once __DIR__ . '/Controllers/MedicoController.php';
require_once __DIR__ . '/Controllers/AgendamentoController.php';

use Controllers\MedicoController;
use Controllers\AgendamentoController;

// O Gerente da Porta (Roteador)
// Ele olha na URL se o usuário quer 'agendar' ou apenas ver a tela padrão
$acao = isset($_GET['acao']) ? $_GET['acao'] : 'listar';

if ($acao === 'agendar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Se clicar no botão de horário, chama o Garçom de Agendamentos para salvar no banco!
    $controller = new AgendamentoController();
    $controller->salvar();
} else {
    // Se for só abrir a página, chama o Garçom de Médicos para mostrar a tela!
    $controller = new MedicoController();
    $controller->listarDisponibilidade();
}

?>