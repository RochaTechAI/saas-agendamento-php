<?php

namespace Controllers;

// O Garçom precisa conhecer a Cozinha (O Model)
require_once __DIR__ . '/../Models/Medico.php';
use Models\Medico;

class MedicoController {
    
    public function listarDisponibilidade() {
        // 1. Em um SaaS real, pegaríamos o ID da clínica logada. Aqui vamos fixar a clínica 1.
        $clinica_id = 1;
        
        // 2. O Garçom anota o pedido: Que data o usuário escolheu? 
        // Se não escolher nenhuma, mostramos a data de amanhã por padrão.
        $data_desejada = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d', strtotime('+1 day'));

        // 3. O Garçom vai na Cozinha (Model) e pede os médicos livres nessa data
        $medicoModel = new Medico();
        $medicos = $medicoModel->getDisponibilidade($clinica_id, $data_desejada);

        // 4. O Garçom entrega a comida pronta na Mesa (View)
        require_once __DIR__ . '/../Views/medicos_disponiveis.php';
    }
}
?>