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
                $sucesso = $model->marcarConsulta($medico_id, $data_consulta, $hora_inicio, $paciente_nome, $paciente_email, $paciente_telefone);

                if ($sucesso) {
                    $data_formatada = date('d/m/Y', strtotime($data_consulta));
                    $assunto = "Sua consulta está confirmada, {$paciente_nome}!";
                    $corpoEmail = "<div style='font-family: Arial, sans-serif; color: #333; padding: 20px; border: 1px solid #ddd; border-radius: 10px; max-width: 600px; margin: 0 auto;'><h2 style='color: #4f46e5; text-align: center;'>Consulta Confirmada! ✅</h2><p>Olá, <strong>{$paciente_nome}</strong>!</p><p>Sua consulta foi agendada com sucesso.</p><div style='background-color: #f8fafc; padding: 15px; border-radius: 8px; margin: 20px 0;'><p>📅 <strong>Data:</strong> {$data_formatada}</p><p>⏰ <strong>Horário:</strong> {$hora_inicio}</p></div><p>Atenciosamente,<br><strong>Equipe da Clínica</strong></p></div>";
                    Mail::enviar($paciente_email, $paciente_nome, $assunto, $corpoEmail);

                    // MÁGICA: Redireciona de volta para o SLUG da clínica!
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
}
?>