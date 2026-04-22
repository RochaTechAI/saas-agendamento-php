<?php

namespace Models;

require_once __DIR__ . '/../Config/Database.php';
use Config\Database;
use PDO;

class Medico {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getDisponibilidade($clinica_id, $data_desejada, $medico_id_filtro = null) {
        $dia_da_semana = date('w', strtotime($data_desejada));

        $sql = "SELECT m.id, m.nome, m.especialidade, g.hora_inicio, g.hora_fim, g.tempo_consulta
                FROM medicos m
                JOIN grade_horarios g ON m.id = g.medico_id
                WHERE m.clinica_id = :clinica_id AND g.dia_da_semana = :dia_semana";

        $params =['clinica_id' => $clinica_id, 'dia_semana' => $dia_da_semana];

        // Se o paciente escolheu um médico no filtro, adicionamos na busca!
        if (!empty($medico_id_filtro)) {
            $sql .= " AND m.id = :medico_id";
            $params['medico_id'] = $medico_id_filtro;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $medicos = $stmt->fetchAll();
        $resultado_final =[];

        foreach ($medicos as $medico) {
            $sqlAgendamentos = "SELECT hora_inicio FROM agendamentos WHERE medico_id = :medico_id AND data_consulta = :data_consulta AND status = 'agendado'";
            $stmtAg = $this->db->prepare($sqlAgendamentos);
            $stmtAg->execute(['medico_id' => $medico['id'], 'data_consulta' => $data_desejada]);
            $horarios_ocupados = $stmtAg->fetchAll(PDO::FETCH_COLUMN);

            $horarios_livres =[];
            $inicio_expediente = strtotime($medico['hora_inicio']);
            $fim_expediente = strtotime($medico['hora_fim']);
            $duracao_segundos = $medico['tempo_consulta'] * 60;

            for ($tempo = $inicio_expediente; $tempo < $fim_expediente; $tempo += $duracao_segundos) {
                $horario_banco = date('H:i:s', $tempo);
                if (!in_array($horario_banco, $horarios_ocupados)) {
                    $horarios_livres[] = date('H:i', $tempo);
                }
            }

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

    public function listarPorClinica($clinica_id) {
        $sql = "SELECT * FROM medicos WHERE clinica_id = :clinica_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['clinica_id' => $clinica_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cadastrar($clinica_id, $nome, $especialidade, $dias_semana, $hora_inicio, $hora_fim, $tempo_consulta) {
        try {
            $this->db->beginTransaction();
            $sqlMedico = "INSERT INTO medicos (clinica_id, nome, especialidade) VALUES (:clinica_id, :nome, :especialidade)";
            $stmt = $this->db->prepare($sqlMedico);
            $stmt->execute(['clinica_id' => $clinica_id, 'nome' => $nome, 'especialidade' => $especialidade]);
            $medico_id = $this->db->lastInsertId();

            $sqlGrade = "INSERT INTO grade_horarios (medico_id, dia_da_semana, hora_inicio, hora_fim, tempo_consulta) VALUES (:medico_id, :dia, :hora_inicio, :hora_fim, :tempo_consulta)";
            $stmtGrade = $this->db->prepare($sqlGrade);

            foreach ($dias_semana as $dia) {
                $stmtGrade->execute(['medico_id' => $medico_id, 'dia' => $dia, 'hora_inicio' => $hora_inicio, 'hora_fim' => $hora_fim, 'tempo_consulta' => $tempo_consulta]);
            }
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
?>