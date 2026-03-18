<?php

namespace Controllers;

require_once __DIR__ . '/../Models/Medico.php';
use Models\Medico;

class MedicoController {
    
    public function listarDisponibilidade() {
        $clinica_id = 1;
        
        // SEGURANÇA EXTRA: Sanitização de Dados (Limpando o que vem da URL)
        // Se houver alguma tentativa de injetar código HTML ou SQL na data, nós limpamos.
        if (isset($_GET['data'])) {
            // Remove qualquer sujeira ou código malicioso
            $data_limpa = htmlspecialchars(strip_tags($_GET['data']));
            $data_desejada = $data_limpa;
        } else {
            $data_desejada = date('Y-m-d', strtotime('+1 day'));
        }

        $medicoModel = new Medico();
        $medicos = $medicoModel->getDisponibilidade($clinica_id, $data_desejada);

        require_once __DIR__ . '/../Views/medicos_disponiveis.php';
    }
}
?>