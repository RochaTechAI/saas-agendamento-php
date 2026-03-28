<?php

namespace Controllers;

require_once __DIR__ . '/../Models/Agendamento.php';
use Models\Agendamento;

class AgendamentoController {
    
    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // 1. Pegando os dados de data e hora do formulário oculto
            $medico_id = $_POST['medico_id'] ?? null;
            $data_consulta = $_POST['data_consulta'] ?? null;
            $hora_inicio = $_POST['hora_inicio'] ?? null;
            
            // 2. Pegando os dados REAIS que o paciente digitou no Modal (Pop-up)
            $paciente_nome = htmlspecialchars(strip_tags($_POST['paciente_nome'] ?? ''));
            $paciente_email = filter_var($_POST['paciente_email'] ?? '', FILTER_SANITIZE_EMAIL);
            $paciente_telefone = htmlspecialchars(strip_tags($_POST['paciente_telefone'] ?? ''));

            // Verifica se as informações principais chegaram
            if ($medico_id && $data_consulta && $hora_inicio && !empty($paciente_nome) && !empty($paciente_email)) {
                
                $model = new Agendamento();
                // AQUI ESTÁ A CORREÇÃO: Enviando os 6 dados certinhos para o Model!
                $sucesso = $model->marcarConsulta($medico_id, $data_consulta, $hora_inicio, $paciente_nome, $paciente_email, $paciente_telefone);

                if ($sucesso) {
                    header("Location: index.php?data=" . $data_consulta . "&sucesso=1");
                    exit;
                } else {
                    header("Location: index.php?data=" . $data_consulta . "&erro_ocupado=1");
                    exit;
                }
            } else {
                echo "<h1>Erro de Segurança: Dados do paciente incompletos ou inválidos.</h1>";
            }
        }
    }
}
?>