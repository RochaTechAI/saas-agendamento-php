<?php

// Importamos o nosso Controller
require_once __DIR__ . '/Controllers/MedicoController.php';
use Controllers\MedicoController;

// Instanciamos o Controller e mandamos ele listar a disponibilidade
$controller = new MedicoController();
$controller->listarDisponibilidade();

?>