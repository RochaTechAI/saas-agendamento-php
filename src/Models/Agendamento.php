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
     * Função responsável por INSERIR a nova consulta no Banco de Dados
     */
    public function marcarConsulta($medico_id, $data_consulta, $hora_inicio, $paciente_nome) {
        
        // 1. O nosso banco exige uma 'hora_fim'. Para simplificar, vamos somar 30 minutos na hora de início.
        $hora_fim = date('H:i:s', strtotime($hora_inicio . ' + 30 minutes'));

        // 2. Preparamos a instrução de INSERT (Atenção aos 'dois pontos' : que evitam invasões de hackers)
        $sql = "INSERT INTO agendamentos (medico_id, data_consulta, hora_inicio, hora_fim, paciente_nome, status) 
                VALUES (:medico_id, :data_consulta, :hora_inicio, :hora_fim, :paciente_nome, 'agendado')";
        
        // 3. O PDO prepara a "Mochila" de segurança
        $stmt = $this->db->prepare($sql);

        // 4. Executamos enviando os dados reais que o Garçom (Controller) vai nos passar
        $sucesso = $stmt->execute([
            'medico_id'     => $medico_id,
            'data_consulta' => $data_consulta,
            'hora_inicio'   => $hora_inicio,
            'hora_fim'      => $hora_fim,
            'paciente_nome' => $paciente_nome
        ]);

        return $sucesso; // Retorna VERDADEIRO se salvou, ou FALSO se deu erro
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
}
?>