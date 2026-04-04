<?php

// ATENÇÃO: Este arquivo NÃO é acessado pelo navegador. 
// Ele é rodado automaticamente pelo servidor Linux (Crontab) todos os dias de manhã.

require_once __DIR__ . '/Config/Database.php';
require_once __DIR__ . '/Config/Mail.php';

use Config\Database;
use Config\Mail;

echo "🤖 Iniciando robô de lembretes...\n";

try {
    $db = Database::getConnection();

    // 1. Descobre qual é a data de "Amanhã"
    $amanha = date('Y-m-d', strtotime('+1 day'));
    echo "Buscando consultas para a data: {$amanha}\n";

    // 2. Busca no banco todos os pacientes agendados para amanhã
    $sql = "SELECT a.paciente_nome, a.paciente_email, a.hora_inicio, m.nome as medico_nome 
            FROM agendamentos a 
            JOIN medicos m ON a.medico_id = m.id 
            WHERE a.data_consulta = :amanha AND a.status = 'agendado'";
            
    $stmt = $db->prepare($sql);
    $stmt->execute(['amanha' => $amanha]);
    $consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($consultas)) {
        echo "Nenhuma consulta agendada para amanhã. Processo finalizado.\n";
        exit;
    }

    // 3. Loop de Disparo de Lembretes
    $enviados = 0;
    foreach ($consultas as $consulta) {
        $assunto = "Lembrete: Sua consulta é AMANHÃ!";
        $data_formatada = date('d/m/Y', strtotime($amanha));
        
        $corpoEmail = "
            <div style='font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;'>
                <h2 style='color: #f59e0b; text-align: center;'>Lembrete de Consulta ⏰</h2>
                <p>Olá, <strong>{$consulta['paciente_nome']}</strong>!</p>
                <p>Este é um lembrete automático de que você tem uma consulta marcada para <strong>amanhã</strong>.</p>
                <div style='background-color: #fef3c7; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                    <p>👨‍⚕️ <strong>Médico:</strong> {$consulta['medico_nome']}</p>
                    <p>📅 <strong>Data:</strong> {$data_formatada}</p>
                    <p>⏰ <strong>Horário:</strong> {$consulta['hora_inicio']}</p>
                </div>
                <p>Por favor, chegue com 10 minutos de antecedência.</p>
                <p>Atenciosamente,<br><strong>Equipe MedSaaS</strong></p>
            </div>
        ";

        // Dispara o e-mail
        if (Mail::enviar($consulta['paciente_email'], $consulta['paciente_nome'], $assunto, $corpoEmail)) {
            echo "✅ Lembrete enviado com sucesso para: {$consulta['paciente_email']}\n";
            $enviados++;
        } else {
            echo "❌ Falha ao enviar para: {$consulta['paciente_email']}\n";
        }
    }

    echo "🏁 Processo finalizado! {$enviados} lembretes disparados.\n";

} catch (Exception $e) {
    echo "Erro Crítico no Robô de Lembretes: " . $e->getMessage() . "\n";
}

?>