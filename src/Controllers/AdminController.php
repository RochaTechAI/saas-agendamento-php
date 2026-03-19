<?php

namespace Controllers;

require_once __DIR__ . '/../Models/Agendamento.php';
use Models\Agendamento;

class AdminController {
    
    public function painel() {
        $clinica_id = 1; // ID fixo para o nosso teste
        
        // Pega a data da URL ou usa a data de amanhã como padrão
        $data_desejada = isset($_GET['data']) ? htmlspecialchars(strip_tags($_GET['data'])) : date('Y-m-d', strtotime('+1 day'));

        // Vai no "Cérebro" e busca a lista de pacientes daquele dia
        $model = new Agendamento();
        $agendamentos = $model->getAgendamentosPorData($clinica_id, $data_desejada);

        // Manda os dados para a tela (View) do Painel
        require_once __DIR__ . '/../Views/dashboard.php';
    }
}
?>