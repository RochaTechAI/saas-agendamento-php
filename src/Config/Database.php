<?php

namespace Config;

use PDO;
use PDOException;

class Database {
    // Estas informações batem com o que colocamos no docker-compose.yml
    private static $host = 'db'; // 'db' é o nome da caixinha do MySQL no Docker
    private static $db_name = 'saas_db';
    private static $username = 'root';
    private static $password = 'root';
    
    // Variável que vai guardar a conexão
    private static $conn = null;

    // Padrão Singleton: Garante apenas uma conexão ativa
    public static function getConnection() {
        if (self::$conn === null) {
            try {
                // Tenta criar a conexão usando PDO (Padrão ouro de segurança no PHP)
                $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$db_name . ";charset=utf8mb4";
                self::$conn = new PDO($dsn, self::$username, self::$password);
                
                // Configura para mostrar erros caso algo dê errado
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // Configura para retornar os dados como um Array associativo
                self::$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                
            } catch(PDOException $exception) {
                // Se der erro, mostra na tela e para o sistema
                die("Erro crítico de conexão com o Banco: " . $exception->getMessage());
            }
        }
        return self::$conn;
    }
}