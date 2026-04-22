<?php

namespace Controllers;

require_once __DIR__ . '/../Models/Agendamento.php';
require_once __DIR__ . '/../Config/Mail.php';

use Models\Agendamento;
use Config\Mail;

class AgendamentoController {
    
    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $clinica_slug = $_POST['clinica_slug'] ?? 'vida-saudavel';
            $medico_id = $_POST['medico_id'] ?? null;
            $data_consulta = $_POST['data_consulta'] ?? null;
            $hora_inicio = $_POST['hora_inicio'] ?? null;
            
            $paciente_nome = htmlspecialchars(strip_tags($_POST['paciente_nome'] ?? ''));
            $paciente_email = filter_var($_POST['paciente_email'] ?? '', FILTER_SANITIZE_EMAIL);
            $paciente_telefone = htmlspecialchars(strip_tags($_POST['paciente_telefone'] ?? ''));

            if ($medico_id && $data_consulta && $hora_inicio && !empty($paciente_nome) && !empty($paciente_email)) {
                
                $model = new Agendamento();
                $token_gerado = $model->marcarConsulta($medico_id, $data_consulta, $hora_inicio, $paciente_nome, $paciente_email, $paciente_telefone);

                if ($token_gerado) {
                    $data_formatada = date('d/m/Y', strtotime($data_consulta));
                    $assunto = "Sua consulta está confirmada, {$paciente_nome}!";
                    $link_cancelamento = "http://localhost:8000/index.php?acao=cancelar_reserva&token=" . $token_gerado;

                    $corpoEmail = "
                    <div style='font-family: Arial, sans-serif; color: #333; padding: 20px; border: 1px solid #ddd; border-radius: 10px; max-width: 600px; margin: 0 auto;'>
                        <h2 style='color: #4f46e5; text-align: center;'>Consulta Confirmada! ✅</h2>
                        <p>Olá, <strong>{$paciente_nome}</strong>!</p>
                        <p>Sua consulta foi agendada com sucesso.</p>
                        <div style='background-color: #f8fafc; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                            <p>📅 <strong>Data:</strong> {$data_formatada}</p>
                            <p>⏰ <strong>Horário:</strong> {$hora_inicio}</p>
                        </div>
                        <p>Imprevistos acontecem. Se precisar cancelar, basta clicar no link abaixo:</p>
                        <div style='text-align: center; margin: 30px 0;'>
                            <a href='{$link_cancelamento}' style='background-color: #dc2626; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Cancelar Consulta</a>
                        </div>
                        <p>Atenciosamente,<br><strong>Equipe da Clínica</strong></p>
                    </div>";
                    
                    Mail::enviar($paciente_email, $paciente_nome, $assunto, $corpoEmail);

                    header("Location: index.php?c=" . $clinica_slug . "&sucesso=1");
                    exit;
                } else {
                    header("Location: index.php?c=" . $clinica_slug . "&erro_ocupado=1");
                    exit;
                }
            } else {
                echo "<h1>Erro: Dados inválidos.</h1>";
            }
        }
    }

    // NOVA LÓGICA: Lê o token do link do e-mail e mostra a TELA DE CONFIRMAÇÃO
    public function confirmarCancelamentoPeloPaciente() {
        $token = $_GET['token'] ?? null;

        if ($token) {
            $model = new Agendamento();
            $consulta = $model->buscarPorToken($token);

            if ($consulta) {
                // Se o token existe, carrega a tela de confirmação enviando os dados pra lá!
                require_once __DIR__ . '/../Views/confirmar_cancelamento.php';
                exit;
            }
        }
        // Se o token é inválido ou já foi usado
        header("Location: index.php?erro_token=1");
        exit;
    }

    // A MÁGICA: Recebe o POST do botão "Sim, cancelar" da tela de confirmação e deleta de verdade
    public function efetivarCancelamento() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'] ?? null;

            if ($token) {
                $model = new Agendamento();
                $cancelou = $model->cancelarPorToken($token);

                if ($cancelou) {
                    header("Location: index.php?cancelado_sucesso=1");
                    exit;
                }
            }
        }
        header("Location: index.php?erro_token=1");
        exit;
    }
}
?>