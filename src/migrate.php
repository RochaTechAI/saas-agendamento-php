<?php

/**
 * Script de migração e seed do banco de dados.
 * Execute uma vez após subir os containers:
 *   docker-compose exec app php /var/www/html/migrate.php
 */

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/Config/Database.php';

use Config\Database;

try {
    $db = Database::getConnection();
    echo "<h2>Atualizando o banco de dados...</h2>";

    $db->exec("CREATE TABLE IF NOT EXISTS clinicas (
        id    INT AUTO_INCREMENT PRIMARY KEY,
        nome  VARCHAR(100) NOT NULL,
        ativo BOOLEAN DEFAULT TRUE
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS medicos (
        id           INT AUTO_INCREMENT PRIMARY KEY,
        clinica_id   INT NOT NULL,
        nome         VARCHAR(100) NOT NULL,
        especialidade VARCHAR(100),
        FOREIGN KEY (clinica_id) REFERENCES clinicas(id) ON DELETE CASCADE
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS grade_horarios (
        id             INT AUTO_INCREMENT PRIMARY KEY,
        medico_id      INT NOT NULL,
        dia_da_semana  INT NOT NULL,
        hora_inicio    TIME NOT NULL,
        hora_fim       TIME NOT NULL,
        tempo_consulta INT DEFAULT 30,
        FOREIGN KEY (medico_id) REFERENCES medicos(id) ON DELETE CASCADE
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS agendamentos (
        id                INT AUTO_INCREMENT PRIMARY KEY,
        medico_id         INT NOT NULL,
        data_consulta     DATE NOT NULL,
        hora_inicio       TIME NOT NULL,
        hora_fim          TIME NOT NULL,
        paciente_nome     VARCHAR(100),
        paciente_email    VARCHAR(100),
        paciente_telefone VARCHAR(20),
        status            ENUM('agendado','cancelado','concluido') DEFAULT 'agendado',
        FOREIGN KEY (medico_id) REFERENCES medicos(id) ON DELETE CASCADE
    )");

    // Adiciona colunas caso a tabela já existisse em versões anteriores
    try {
        $db->exec("ALTER TABLE agendamentos ADD COLUMN paciente_email    VARCHAR(100) NULL AFTER paciente_nome");
        $db->exec("ALTER TABLE agendamentos ADD COLUMN paciente_telefone VARCHAR(20)  NULL AFTER paciente_email");
    } catch (PDOException $e) { /* Ignora se as colunas já existirem */ }

    $db->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id         INT AUTO_INCREMENT PRIMARY KEY,
        clinica_id INT NOT NULL,
        nome       VARCHAR(100) NOT NULL,
        email      VARCHAR(100) NOT NULL UNIQUE,
        senha      VARCHAR(255) NOT NULL,
        FOREIGN KEY (clinica_id) REFERENCES clinicas(id) ON DELETE CASCADE
    )");

    // ── Seed inicial ──────────────────────────────────────────────────────────
    $stmt = $db->query("SELECT COUNT(*) FROM clinicas");
    if ($stmt->fetchColumn() == 0) {
        $db->exec("INSERT INTO clinicas (nome) VALUES ('Clínica Vida Saudável')");
        $clinicaId = $db->lastInsertId();

        $db->exec("INSERT INTO medicos (clinica_id, nome, especialidade)
                   VALUES ($clinicaId, 'Dr. Carlos Silva', 'Cardiologista')");
        $medicoId = $db->lastInsertId();

        for ($dia = 1; $dia <= 5; $dia++) {
            $db->exec("INSERT INTO grade_horarios (medico_id, dia_da_semana, hora_inicio, hora_fim, tempo_consulta)
                       VALUES ($medicoId, $dia, '09:00:00', '17:00:00', 30)");
        }
    }

    $stmtUser = $db->query("SELECT COUNT(*) FROM usuarios");
    if ($stmtUser->fetchColumn() == 0) {
        $senha = password_hash('senha123', PASSWORD_DEFAULT);
        $db->exec("INSERT INTO usuarios (clinica_id, nome, email, senha)
                   VALUES (1, 'Admin Clínica', 'admin@medsaas.com', '$senha')");
    }

    echo "<h3 style='color: green;'>Banco de dados pronto!</h3>";
} catch (Exception $e) {
    echo "<h3 style='color: red;'>Erro: " . htmlspecialchars($e->getMessage()) . "</h3>";
}
