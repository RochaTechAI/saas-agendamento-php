<?php

namespace Models;

// Importamos a nossa conexão de banco de dados e o PDO
require_once __DIR__ . '/../Config/Database.php';
use Config\Database;
use PDO;

class Medico {
    private $db;

    public function __construct() {
        // Toda vez que usarmos o Medico, ele já pega a conexão com o banco automaticamente
        $this->db = Database::getConnection();
    }

    /**
     * O Algoritmo de Ouro: Busca os médicos e seus horários disponíveis
     */
    public function getDisponibilidade($clinica_id, $data_desejada) {
        
        // 1. Descobre qual dia da semana é a data escolhida (1 = Segunda, ..., 5 = Sexta)
        $dia_da_semana = date('N', strtotime($data_desejada));

        // 2. Busca no banco todos os médicos desta clínica que trabalham neste dia
        $sql = "
            SELECT m.id, m.nome, m.especialidade, g.hora_inicio, g.hora_fim, g.tempo_consulta
            FROM medicos m
            JOIN grade_horarios g ON m.id = g.medico_id
            WHERE m.clinica_id = :clinica_id AND g.dia_da_semana = :dia_semana
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'clinica_id' => $clinica_id,
            'dia_semana' => $dia_da_semana
        ]);

        $medicos = $stmt->fetchAll();
        $resultado_final =[];

        // 3. Para cada médico encontrado, vamos calcular os horários
        foreach ($medicos as $medico) {
            
            // Vai no banco e pega os horários que já estão ocupados (agendados) neste dia
            $sqlAgendamentos = "
                SELECT hora_inicio 
                FROM agendamentos 
                WHERE medico_id = :medico_id 
                AND data_consulta = :data_consulta 
                AND status = 'agendado'
            ";
            
            $stmtAg = $this->db->prepare($sqlAgendamentos);
            $stmtAg->execute([
                'medico_id' => $medico['id'],
                'data_consulta' => $data_desejada
            ]);

            // Pega os horários ocupados e transforma numa lista simples, ex:['09:00:00', '10:30:00']
            $horarios_ocupados = $stmtAg->fetchAll(PDO::FETCH_COLUMN);

            // 4. A Mágica: Vamos gerar os "espaços" (slots) da agenda
            $horarios_livres =[];
            $inicio_expediente = strtotime($medico['hora_inicio']);
            $fim_expediente = strtotime($medico['hora_fim']);
            $duracao_segundos = $medico['tempo_consulta'] * 60; // Transforma 30 min em segundos

            // O loop vai pulando de 30 em 30 minutos desde a hora que ele entra até a hora que sai
            for ($tempo = $inicio_expediente; $tempo < $fim_expediente; $tempo += $duracao_segundos) {
                
                $horario_banco = date('H:i:s', $tempo); // Formato para comparar com o banco

                // Se o horário NÃO estiver na lista de ocupados, nós separamos ele como LIVRE!
                if (!in_array($horario_banco, $horarios_ocupados)) {
                    $horarios_livres[] = date('H:i', $tempo); // Formato bonito para a tela (ex: 09:30)
                }
            }

            // Se sobrou algum horário livre, adicionamos o médico na lista final
            if (count($horarios_livres) > 0) {
                $resultado_final[] = [
                    'id' => $medico['id'],
                    'nome' => $medico['nome'],
                    'especialidade' => $medico['especialidade'],
                    'horarios_disponiveis' => $horarios_livres
                ];
            }
        }

        return $resultado_final;
    }
}
?>