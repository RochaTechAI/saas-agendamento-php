<?php

namespace Config;

use PDO;
use PDOException;

class Database {
    private static $conn = null;

    public static function getConnection() {
        if (self::$conn === null) {
            try {
                // O Docker injeta as variáveis do .env magicamente aqui!
                // Usamos o getenv() nativo do PHP.
                $host     = getenv('DB_HOST') ?: 'db';
                $db_name  = getenv('DB_NAME') ?: 'saas_db';
                $username = getenv('DB_USER') ?: 'root';
                $password = getenv('DB_PASS') ?: 'root';

                $dsn = "mysql:host={$host};dbname={$db_name};charset=utf8mb4";
                self::$conn = new PDO($dsn, $username, $password);
                
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                
            } catch(PDOException $exception) {
                die("Erro de conexão com o banco de dados.<br><br><b>Detalhe técnico:</b> " . $exception->getMessage());
            }
        }
        return self::$conn;
    }
}
?>