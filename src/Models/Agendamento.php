<?php

namespace Models;

require_once __DIR__ . '/../Config/Database.php';
use Config\Database;
use PDO;

class Agendamento {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function marcarConsulta($medico_id, $data_consulta, $hora_inicio, $paciente_nome, $paciente_email, $paciente_telefone) {
        $hora_fim = date('H:i:s', strtotime($hora_inicio . ' + 30 minutes'));
        $token = bin2hex(random_bytes(16));

        try {
            $this->db->beginTransaction();

            $sqlCheck = "SELECT id FROM agendamentos WHERE medico_id = :medico_id AND data_consulta = :data_consulta AND hora_inicio = :hora_inicio AND status = 'agendado' FOR UPDATE";
            $stmtCheck = $this->db->prepare($sqlCheck);
            $stmtCheck->execute(['medico_id' => $medico_id, 'data_consulta' => $data_consulta, 'hora_inicio' => $hora_inicio]);

            if ($stmtCheck->rowCount() > 0) {
                $this->db->rollBack(); 
                return false; 
            }

            $sqlInsert = "INSERT INTO agendamentos (medico_id, data_consulta, hora_inicio, hora_fim, paciente_nome, paciente_email, paciente_telefone, status, token_cancelamento) 
                          VALUES (:medico_id, :data_consulta, :hora_inicio, :hora_fim, :paciente_nome, :paciente_email, :paciente_telefone, 'agendado', :token)";
            
            $stmtInsert = $this->db->prepare($sqlInsert);
            $stmtInsert->execute([
                'medico_id'         => $medico_id,
                'data_consulta'     => $data_consulta,
                'hora_inicio'       => $hora_inicio,
                'hora_fim'          => $hora_fim,
                'paciente_nome'     => $paciente_nome,
                'paciente_email'    => $paciente_email,
                'paciente_telefone' => $paciente_telefone,
                'token'             => $token
            ]);

            $this->db->commit();
            return $token;

        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getAgendamentosPorData($clinica_id, $data) {
        $sql = "SELECT a.id, a.hora_inicio, a.paciente_nome, a.paciente_email, a.paciente_telefone, a.status, m.nome as medico_nome
                FROM agendamentos a JOIN medicos m ON a.medico_id = m.id
                WHERE m.clinica_id = :clinica_id AND a.data_consulta = :data ORDER BY a.hora_inicio ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['clinica_id' => $clinica_id, 'data' => $data]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizarStatus($agendamento_id, $novo_status, $clinica_id) {
        $sql = "UPDATE agendamentos a JOIN medicos m ON a.medico_id = m.id SET a.status = :status WHERE a.id = :id AND m.clinica_id = :clinica_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['status' => $novo_status, 'id' => $agendamento_id, 'clinica_id' => $clinica_id]);
    }

    // NOVA FUNÇÃO: Busca os detalhes da consulta usando o Token secreto
    public function buscarPorToken($token) {
        $sql = "SELECT a.id, a.data_consulta, a.hora_inicio, m.nome as medico_nome
                FROM agendamentos a JOIN medicos m ON a.medico_id = m.id
                WHERE a.token_cancelamento = :token AND a.status = 'agendado'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function cancelarPorToken($token) {
        $sql = "UPDATE agendamentos SET status = 'cancelado' WHERE token_cancelamento = :token AND status = 'agendado'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['token' => $token]);
        return $stmt->rowCount() > 0;
    }
}
?>