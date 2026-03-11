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
}
?>