<?php

namespace Controllers;

require_once __DIR__ . '/../Models/Agendamento.php';
use Models\Agendamento;

class AgendamentoController {
    
    public function salvar() {
        // Verifica se os dados vieram pelo formulário que está na View
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // 1. Pegamos os dados EXATOS que os botões (inputs hidden) enviaram
            $medico_id = $_POST['medico_id'] ?? null;
            $data_consulta = $_POST['data_consulta'] ?? null;
            $hora_inicio = $_POST['hora_inicio'] ?? null;
            
            // 2. Simulamos um nome de paciente
            $paciente_nome = "Paciente VIP " . rand(100, 999);

            // Verifica se não está faltando nada
            if ($medico_id && $data_consulta && $hora_inicio) {
                
                // 3. Chamamos o "Cozinheiro" (Model) com a função BLINDADA
                $model = new Agendamento();
                $sucesso = $model->marcarConsulta($medico_id, $data_consulta, $hora_inicio, $paciente_nome);

                if ($sucesso) {
                    // Se salvou no banco, volta para a tela inicial mostrando o aviso VERDE!
                    header("Location: index.php?data=" . $data_consulta . "&sucesso=1");
                    exit;
                } else {
                    // Se deu falso (alguém pegou a vaga antes), manda o aviso VERMELHO!
                    header("Location: index.php?data=" . $data_consulta . "&erro_ocupado=1");
                    exit;
                }
            } else {
                echo "<h1>Dados incompletos! O botão não enviou todas as informações.</h1>";
            }
        }
    }
}
?>