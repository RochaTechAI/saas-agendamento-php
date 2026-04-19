<?php

namespace Controllers;

require_once __DIR__ . '/../Models/Agendamento.php';
require_once __DIR__ . '/../Models/Medico.php';

use Models\Agendamento;
use Models\Medico;

class AdminController {
    
    public function painel() {
        $clinica_id = $_SESSION['clinica_id']; 
        $data_desejada = isset($_GET['data']) ? htmlspecialchars(strip_tags($_GET['data'])) : date('Y-m-d', strtotime('+1 day'));

        $model = new Agendamento();
        $agendamentos = $model->getAgendamentosPorData($clinica_id, $data_desejada);
        require_once __DIR__ . '/../Views/dashboard.php';
    }

    public function alterarStatus() {
        $id_agendamento = $_GET['id'] ?? null;
        $novo_status = $_GET['status'] ?? null;
        $data_atual = $_GET['data'] ?? date('Y-m-d'); 
        
        $clinica_id = $_SESSION['clinica_id'];

        if ($id_agendamento && in_array($novo_status,['concluido', 'cancelado'])) {
            $model = new Agendamento();
            $model->atualizarStatus($id_agendamento, $novo_status, $clinica_id);
        }
        header("Location: index.php?acao=painel&data=" . $data_atual);
        exit;
    }
    
    public function medicos() {
        $clinica_id = $_SESSION['clinica_id'];
        $medicoModel = new Medico();
        $medicos = $medicoModel->listarPorClinica($clinica_id);
        
        require_once __DIR__ . '/../Views/medicos_admin.php';
    }

    public function salvarMedico() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $clinica_id = $_SESSION['clinica_id'];
            
            $nome = htmlspecialchars(strip_tags($_POST['nome'] ?? ''));
            $especialidade = htmlspecialchars(strip_tags($_POST['especialidade'] ?? ''));
            
            // A MÁGICA DO CONTROLADOR: Pegando os dias que o usuário marcou (Array)
            $dias_semana = $_POST['dias_semana'] ?? []; 
            
            $hora_inicio = $_POST['hora_inicio'] ?? '08:00';
            $hora_fim = $_POST['hora_fim'] ?? '18:00';
            $tempo_consulta = (int) ($_POST['tempo_consulta'] ?? 30);

            // Só cadastra se o nome e os dias de atendimento não estiverem vazios
            if (!empty($nome) && !empty($especialidade) && !empty($dias_semana)) {
                $model = new Medico();
                $model->cadastrar($clinica_id, $nome, $especialidade, $dias_semana, $hora_inicio, $hora_fim, $tempo_consulta);
            }
            
            header("Location: index.php?acao=painel_medicos&sucesso=1");
            exit;
        }
    }
}
?>