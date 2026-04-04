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

    // Recebe o clique do botão Concluir ou Cancelar no Dashboard
    public function alterarStatus() {
        // Pega os dados que vieram pela URL (Link do botão)
        $id_agendamento = $_GET['id'] ?? null;
        $novo_status = $_GET['status'] ?? null;
        $data_atual = $_GET['data'] ?? date('Y-m-d'); // Para sabermos pra qual dia voltar
        
        // Pega a clínica do usuário que está logado (Segurança!)
        $clinica_id = $_SESSION['clinica_id'];

        // Só faz a alteração se o status for válido
        if ($id_agendamento && in_array($novo_status, ['concluido', 'cancelado'])) {
            $model = new Agendamento();
            $model->atualizarStatus($id_agendamento, $novo_status, $clinica_id);
        }

        // Redireciona de volta para a tabela no mesmo dia que a recepcionista estava olhando
        header("Location: index.php?acao=painel&data=" . $data_atual);
        exit;
    }
}
?>