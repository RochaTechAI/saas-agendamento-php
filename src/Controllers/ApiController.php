<?php

namespace Controllers;

require_once __DIR__ . '/../Models/Medico.php';
use Models\Medico;

class ApiController {
    
    public function getDisponibilidade() {
        header('Content-Type: application/json; charset=utf-8');
        $clinica_id = 1; 
        
        $data_desejada = isset($_GET['data']) ? htmlspecialchars(strip_tags($_GET['data'])) : date('Y-m-d', strtotime('+1 day'));
        
        // Pegando o filtro do médico que o Vue.js enviar
        $medico_id = (isset($_GET['medico']) && $_GET['medico'] !== '') ? (int) $_GET['medico'] : null;

        $medicoModel = new Medico();
        $medicos = $medicoModel->getDisponibilidade($clinica_id, $data_desejada, $medico_id);

        if (empty($medicos)) {
            http_response_code(404);
            echo json_encode(["mensagem" => "Nenhum médico disponível."]);
        } else {
            http_response_code(200);
            echo json_encode([
                "data_buscada" => $data_desejada,
                "total_medicos" => count($medicos),
                "medicos" => $medicos
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    // NOVA ROTA: Retorna todos os médicos para o dropdown da tela do paciente
    public function getMedicosClinica() {
        header('Content-Type: application/json; charset=utf-8');
        $clinica_id = 1;
        
        $medicoModel = new Medico();
        $medicos = $medicoModel->listarPorClinica($clinica_id);
        
        http_response_code(200);
        echo json_encode($medicos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
?>