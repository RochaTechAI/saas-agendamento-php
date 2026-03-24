<?php

require_once __DIR__ . '/Config/Database.php';
use Config\Database;

try {
    $db = Database::getConnection();
    echo "<h2>Atualizando o Banco de Dados... 🛠️</h2>";

    // Cria as tabelas que já existiam
    $db->exec("CREATE TABLE IF NOT EXISTS clinicas (id INT AUTO_INCREMENT PRIMARY KEY, nome VARCHAR(100) NOT NULL, ativo BOOLEAN DEFAULT TRUE)");
    $db->exec("CREATE TABLE IF NOT EXISTS medicos (id INT AUTO_INCREMENT PRIMARY KEY, clinica_id INT NOT NULL, nome VARCHAR(100) NOT NULL, especialidade VARCHAR(100), FOREIGN KEY (clinica_id) REFERENCES clinicas(id) ON DELETE CASCADE)");
    $db->exec("CREATE TABLE IF NOT EXISTS grade_horarios (id INT AUTO_INCREMENT PRIMARY KEY, medico_id INT NOT NULL, dia_da_semana INT NOT NULL, hora_inicio TIME NOT NULL, hora_fim TIME NOT NULL, tempo_consulta INT DEFAULT 30, FOREIGN KEY (medico_id) REFERENCES medicos(id) ON DELETE CASCADE)");
    $db->exec("CREATE TABLE IF NOT EXISTS agendamentos (id INT AUTO_INCREMENT PRIMARY KEY, medico_id INT NOT NULL, data_consulta DATE NOT NULL, hora_inicio TIME NOT NULL, hora_fim TIME NOT NULL, paciente_nome VARCHAR(100), status ENUM('agendado', 'cancelado', 'concluido') DEFAULT 'agendado', FOREIGN KEY (medico_id) REFERENCES medicos(id) ON DELETE CASCADE)");

    // 1. A NOVA TABELA DE USUÁRIOS
    $db->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        clinica_id INT NOT NULL,
        nome VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL,
        FOREIGN KEY (clinica_id) REFERENCES clinicas(id) ON DELETE CASCADE
    )");
    echo "✅ Tabela 'usuarios' verificada/criada com sucesso!<br>";

    // Inserindo Clínica e Médico (se não existirem)
    $stmt = $db->query("SELECT COUNT(*) FROM clinicas");
    if ($stmt->fetchColumn() == 0) {
        $db->exec("INSERT INTO clinicas (nome) VALUES ('Clínica Vida Saudável')");
        $clinica_id = $db->lastInsertId();

        $db->exec("INSERT INTO medicos (clinica_id, nome, especialidade) VALUES ($clinica_id, 'Dr. Carlos Silva', 'Cardiologista')");
        $medico_id = $db->lastInsertId();

        for ($dia = 1; $dia <= 5; $dia++) {
            $db->exec("INSERT INTO grade_horarios (medico_id, dia_da_semana, hora_inicio, hora_fim, tempo_consulta) VALUES ($medico_id, $dia, '09:00:00', '17:00:00', 30)");
        }
    }

    // 2. INSERINDO O USUÁRIO ADMINISTRADOR DE TESTE
    $stmtUser = $db->query("SELECT COUNT(*) FROM usuarios");
    if ($stmtUser->fetchColumn() == 0) {
        $senhaCriptografada = password_hash('senha123', PASSWORD_DEFAULT); // Isso transforma 'senha123' num código ilegível
        $db->exec("INSERT INTO usuarios (clinica_id, nome, email, senha) VALUES (1, 'Admin Clínica', 'admin@medsaas.com', '$senhaCriptografada')");
        echo "<br>🔐 <strong>Usuário Admin criado com sucesso! (Email: admin@medsaas.com / Senha: senha123)</strong>";
    }

    echo "<h3 style='color: green;'>🚀 Tudo pronto!</h3>";

} catch (Exception $e) {
    echo "<h3 style='color: red;'>❌ Erro ao criar o banco: " . $e->getMessage() . "</h3>";
}