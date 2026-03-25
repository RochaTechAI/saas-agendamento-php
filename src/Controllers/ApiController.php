<?php

namespace Controllers;

require_once __DIR__ . '/../Models/Medico.php';
use Models\Medico;

class ApiController {
    
    // Endpoint para buscar a disponibilidade em formato JSON
    public function getDisponibilidade() {
        // 1. Dizemos para quem chamou (Postman/Aplicativo) que a resposta será em JSON
        header('Content-Type: application/json; charset=utf-8');
        
        $clinica_id = 1; 
        
        // 2. Pegamos a data da URL
        $data_desejada = isset($_GET['data']) ? htmlspecialchars(strip_tags($_GET['data'])) : date('Y-m-d', strtotime('+1 day'));

        // 3. Vamos no Cérebro (Model) buscar os dados puros
        $medicoModel = new Medico();
        $medicos = $medicoModel->getDisponibilidade($clinica_id, $data_desejada);

        // 4. Transformamos os dados do PHP em JSON e devolvemos na tela!
        if (empty($medicos)) {
            // Se não tiver médico, devolve um erro 404 (Not Found)
            http_response_code(404);
            echo json_encode(["mensagem" => "Nenhum médico disponível nesta data."]);
        } else {
            // Se tiver, devolve com sucesso (200 OK)
            http_response_code(200);
            echo json_encode([
                "data_buscada" => $data_desejada,
                "total_medicos" => count($medicos),
                "medicos" => $medicos
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }
}
?>