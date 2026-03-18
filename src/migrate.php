<?php

require_once __DIR__ . '/Config/Database.php';
use Config\Database;

try {
    $db = Database::getConnection();
    echo "<h2>Iniciando a criação do Banco de Dados... 🛠️</h2>";

    // 1. Criando a tabela de Clínicas (SaaS Multi-tenant)
    $db->exec("CREATE TABLE IF NOT EXISTS clinicas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        ativo BOOLEAN DEFAULT TRUE
    )");
    echo "✅ Tabela 'clinicas' criada com sucesso!<br>";

    // 2. Criando a tabela de Médicos
    $db->exec("CREATE TABLE IF NOT EXISTS medicos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        clinica_id INT NOT NULL,
        nome VARCHAR(100) NOT NULL,
        especialidade VARCHAR(100),
        FOREIGN KEY (clinica_id) REFERENCES clinicas(id) ON DELETE CASCADE
    )");
    echo "✅ Tabela 'medicos' criada com sucesso!<br>";

    // 3. Criando a tabela de Grade de Horários
    $db->exec("CREATE TABLE IF NOT EXISTS grade_horarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        medico_id INT NOT NULL,
        dia_da_semana INT NOT NULL,
        hora_inicio TIME NOT NULL,
        hora_fim TIME NOT NULL,
        tempo_consulta INT DEFAULT 30,
        FOREIGN KEY (medico_id) REFERENCES medicos(id) ON DELETE CASCADE
    )");
    echo "✅ Tabela 'grade_horarios' criada com sucesso!<br>";

    // 4. Criando a tabela de Agendamentos
    $db->exec("CREATE TABLE IF NOT EXISTS agendamentos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        medico_id INT NOT NULL,
        data_consulta DATE NOT NULL,
        hora_inicio TIME NOT NULL,
        hora_fim TIME NOT NULL,
        paciente_nome VARCHAR(100),
        status ENUM('agendado', 'cancelado', 'concluido') DEFAULT 'agendado',
        FOREIGN KEY (medico_id) REFERENCES medicos(id) ON DELETE CASCADE
    )");
    echo "✅ Tabela 'agendamentos' criada com sucesso!<br>";

    // ==========================================
    // INSERINDO DADOS FALSOS PARA A GENTE TESTAR
    // ==========================================
    
    // Verifica se já existe uma clínica, se não, cria uma.
    $stmt = $db->query("SELECT COUNT(*) FROM clinicas");
    if ($stmt->fetchColumn() == 0) {
        // Cria a Clínica
        $db->exec("INSERT INTO clinicas (nome) VALUES ('Clínica Vida Saudável')");
        $clinica_id = $db->lastInsertId();

        // Cria o Doutor
        $db->exec("INSERT INTO medicos (clinica_id, nome, especialidade) VALUES ($clinica_id, 'Dr. Carlos Silva', 'Cardiologista')");
        $medico_id = $db->lastInsertId();

        // Cria o horário de trabalho do Doutor (Segunda a Sexta, das 09:00 às 17:00, consultas de 30 min)
        // Dias: 1=Seg, 2=Ter, 3=Qua, 4=Qui, 5=Sex
        for ($dia = 1; $dia <= 5; $dia++) {
            $db->exec("INSERT INTO grade_horarios (medico_id, dia_da_semana, hora_inicio, hora_fim, tempo_consulta) 
                       VALUES ($medico_id, $dia, '09:00:00', '17:00:00', 30)");
        }

        // Simula que já existe um paciente agendado para as 09:00 de amanhã
        $amanha = date('Y-m-d', strtotime('+1 day'));
        $db->exec("INSERT INTO agendamentos (medico_id, data_consulta, hora_inicio, hora_fim, paciente_nome) 
                   VALUES ($medico_id, '$amanha', '09:00:00', '09:30:00', 'João Ocupado')");

        echo "<br>🏥 <strong>Dados de teste (Clínica, Médico e Horários) inseridos com sucesso!</strong>";
    }

    echo "<h3 style='color: green;'>🚀 Tudo pronto! O Banco de Dados está preparado.</h3>";

} catch (Exception $e) {
    echo "<h3 style='color: red;'>❌ Erro ao criar o banco: " . $e->getMessage() . "</h3>";
}