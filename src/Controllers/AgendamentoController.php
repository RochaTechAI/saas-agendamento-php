<?php
namespace Controllers;

use Models\Agendamento;

class AgendamentoController {
    
    public function salvar() {
        // 1. Pegamos os dados que o usuário preencheu no formulário
        $medico_id = $_POST['medico_id'] ?? null;
        $data_hora = $_POST['data_hora'] ?? null;
        $paciente_nome = "Paciente Teste"; // Depois podemos pegar de um login

        if ($medico_id && $data_hora) {
            // 2. Chamamos o Model (o "Arquivo Morto") para salvar
            $model = new Agendamento();
            $sucesso = $model->salvarAgendamento($medico_id, $paciente_nome, $data_hora);

            if ($sucesso) {
                echo "<h2>✅ Agendamento realizado com sucesso!</h2>";
                echo "<a href='index.php'>Voltar para a lista</a>";
            } else {
                echo "<h2>❌ Erro ao salvar no banco de dados.</h2>";
            }
        } else {
            echo "<h2>⚠️ Dados incompletos!</h2>";
        }
    }
}