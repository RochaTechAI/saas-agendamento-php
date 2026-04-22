<?php
require_once __DIR__ . '/Config/Database.php';
use Config\Database;

try {
    $db = Database::getConnection();
    echo "<h2>Atualizando o Banco de Dados (Multi-tenant)... 🛠️</h2>";

    // 1. Clínicas (Agora com a coluna SLUG)
    $db->exec("CREATE TABLE IF NOT EXISTS clinicas (id INT AUTO_INCREMENT PRIMARY KEY, nome VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL UNIQUE, ativo BOOLEAN DEFAULT TRUE)");
    
    // Tenta adicionar a coluna caso a tabela seja antiga
    try {
        $db->exec("ALTER TABLE clinicas ADD COLUMN slug VARCHAR(100) NULL AFTER nome");
        $db->exec("UPDATE clinicas SET slug = 'vida-saudavel' WHERE id = 1");
    } catch (\PDOException $e) {}

    // 2. Médicos e Grade
    $db->exec("CREATE TABLE IF NOT EXISTS medicos (id INT AUTO_INCREMENT PRIMARY KEY, clinica_id INT NOT NULL, nome VARCHAR(100) NOT NULL, especialidade VARCHAR(100), FOREIGN KEY (clinica_id) REFERENCES clinicas(id) ON DELETE CASCADE)");
    $db->exec("CREATE TABLE IF NOT EXISTS grade_horarios (id INT AUTO_INCREMENT PRIMARY KEY, medico_id INT NOT NULL, dia_da_semana INT NOT NULL, hora_inicio TIME NOT NULL, hora_fim TIME NOT NULL, tempo_consulta INT DEFAULT 30, FOREIGN KEY (medico_id) REFERENCES medicos(id) ON DELETE CASCADE)");
    
    // 3. Agendamentos (Com os dados do paciente)
    $db->exec("CREATE TABLE IF NOT EXISTS agendamentos (
        id INT AUTO_INCREMENT PRIMARY KEY, medico_id INT NOT NULL, data_consulta DATE NOT NULL, hora_inicio TIME NOT NULL, hora_fim TIME NOT NULL,
        paciente_nome VARCHAR(100), paciente_email VARCHAR(100), paciente_telefone VARCHAR(20), status ENUM('agendado', 'cancelado', 'concluido') DEFAULT 'agendado',
        FOREIGN KEY (medico_id) REFERENCES medicos(id) ON DELETE CASCADE
    )");

    try {
        $db->exec("ALTER TABLE agendamentos ADD COLUMN paciente_email VARCHAR(100) NULL AFTER paciente_nome");
        $db->exec("ALTER TABLE agendamentos ADD COLUMN paciente_telefone VARCHAR(20) NULL AFTER paciente_email");
    } catch (\PDOException $e) {}

    // 4. Usuários Administrativos
    $db->exec("CREATE TABLE IF NOT EXISTS usuarios (id INT AUTO_INCREMENT PRIMARY KEY, clinica_id INT NOT NULL, nome VARCHAR(100) NOT NULL, email VARCHAR(100) NOT NULL UNIQUE, senha VARCHAR(255) NOT NULL, FOREIGN KEY (clinica_id) REFERENCES clinicas(id) ON DELETE CASCADE)");

    // DADOS INICIAIS DE TESTE
    $stmt = $db->query("SELECT COUNT(*) FROM clinicas");
    if ($stmt->fetchColumn() == 0) {
        $db->exec("INSERT INTO clinicas (nome, slug) VALUES ('Clínica Vida Saudável', 'vida-saudavel')");
        $clinica_id = $db->lastInsertId();
        $db->exec("INSERT INTO medicos (clinica_id, nome, especialidade) VALUES ($clinica_id, 'Dr. Carlos Silva', 'Cardiologista')");
        $medico_id = $db->lastInsertId();
        for ($dia = 1; $dia <= 5; $dia++) {
            $db->exec("INSERT INTO grade_horarios (medico_id, dia_da_semana, hora_inicio, hora_fim, tempo_consulta) VALUES ($medico_id, $dia, '09:00:00', '17:00:00', 30)");
        }
    }

    $stmtUser = $db->query("SELECT COUNT(*) FROM usuarios");
    if ($stmtUser->fetchColumn() == 0) {
        $senhaCriptografada = password_hash('senha123', PASSWORD_DEFAULT);
        $db->exec("INSERT INTO usuarios (clinica_id, nome, email, senha) VALUES (1, 'Admin Clínica', 'admin@medsaas.com', '$senhaCriptografada')");
    }

    echo "<h3 style='color: green;'>🚀 O Banco de Dados está atualizado e perfeito!</h3>";
} catch (Exception $e) {
    echo "<h3 style='color: red;'>❌ Erro: " . $e->getMessage() . "</h3>";
}
?>