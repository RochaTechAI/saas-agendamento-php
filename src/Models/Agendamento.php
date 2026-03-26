<?php

namespace Models;

// Importamos a conexão com o Banco de Dados
require_once __DIR__ . '/../Config/Database.php';
use Config\Database;
use PDO;

class Agendamento {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Função responsável por INSERIR a consulta usando TRANSAÇÕES ACID (Prevenção de Double Booking)
     */
    public function marcarConsulta($medico_id, $data_consulta, $hora_inicio, $paciente_nome) {
        $hora_fim = date('H:i:s', strtotime($hora_inicio . ' + 30 minutes'));

        try {
            // 1. INICIA A TRANSAÇÃO: "Tranca a porta do banco de dados"
            $this->db->beginTransaction();

            // 2. VERIFICA SE O HORÁRIO AINDA ESTÁ LIVRE
            // O comando 'FOR UPDATE' é o segredo: ele cria uma fila no MySQL para quem tentar ler isso ao mesmo tempo
            $sqlCheck = "SELECT id FROM agendamentos 
                         WHERE medico_id = :medico_id 
                         AND data_consulta = :data_consulta 
                         AND hora_inicio = :hora_inicio 
                         AND status = 'agendado' 
                         FOR UPDATE";
            
            $stmtCheck = $this->db->prepare($sqlCheck);
            $stmtCheck->execute([
                'medico_id' => $medico_id,
                'data_consulta' => $data_consulta,
                'hora_inicio' => $hora_inicio
            ]);

            // Se o banco retornar 1 linha, significa que alguém já pegou a vaga!
            if ($stmtCheck->rowCount() > 0) {
                // Desfaz qualquer coisa e "Destranca a porta"
                $this->db->rollBack(); 
                return false; // Retorna falso avisando que falhou
            }

            // 3. SE ESTIVER LIVRE, FAZ O INSERT COM SEGURANÇA
            $sqlInsert = "INSERT INTO agendamentos (medico_id, data_consulta, hora_inicio, hora_fim, paciente_nome, status) 
                          VALUES (:medico_id, :data_consulta, :hora_inicio, :hora_fim, :paciente_nome, 'agendado')";
            
            $stmtInsert = $this->db->prepare($sqlInsert);
            $stmtInsert->execute([
                'medico_id'     => $medico_id,
                'data_consulta' => $data_consulta,
                'hora_inicio'   => $hora_inicio,
                'hora_fim'      => $hora_fim,
                'paciente_nome' => $paciente_nome
            ]);

            // 4. SUCESSO! CONFIRMA A GRAVAÇÃO NO DISCO E DESTRANCA A PORTA
            $this->db->commit();
            return true;

        } catch (\Exception $e) {
            // Se o servidor der erro (ex: queda de luz), cancela tudo pra não corromper o banco
            $this->db->rollBack();
            return false;
        }
    }
    /**
     * Função para o Painel: Busca todos os agendamentos de uma clínica em uma data específica
     */
    public function getAgendamentosPorData($clinica_id, $data) {
        // Fazemos um JOIN (junção) para pegar o nome do paciente E o nome do médico
        $sql = "
            SELECT a.id, a.hora_inicio, a.paciente_nome, a.status, m.nome as medico_nome
            FROM agendamentos a
            JOIN medicos m ON a.medico_id = m.id
            WHERE m.clinica_id = :clinica_id 
            AND a.data_consulta = :data
            ORDER BY a.hora_inicio ASC
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'clinica_id' => $clinica_id,
            'data' => $data
        ]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Função para o Painel: Atualiza o status da consulta (Concluir ou Cancelar)
     */
    public function atualizarStatus($agendamento_id, $novo_status, $clinica_id) {
        // Usamos um JOIN por segurança: garante que a recepcionista só pode alterar
        // consultas que pertencem aos médicos da própria clínica dela!
        $sql = "UPDATE agendamentos a
                JOIN medicos m ON a.medico_id = m.id
                SET a.status = :status
                WHERE a.id = :id AND m.clinica_id = :clinica_id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'status'     => $novo_status,
            'id'         => $agendamento_id,
            'clinica_id' => $clinica_id
        ]);
    }
}
?>