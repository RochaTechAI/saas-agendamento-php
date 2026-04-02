<?php

namespace Config;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $conn = null;

    public static function getConnection(): PDO
    {
        if (self::$conn === null) {
            try {
                $host   = getenv('DB_HOST') ?: 'db';
                $dbname = getenv('DB_NAME') ?: 'saas_db';
                $user   = getenv('DB_USER') ?: 'root';
                $pass   = getenv('DB_PASS') ?: '';

                $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

                self::$conn = new PDO($dsn, $user, $pass);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log('Erro de conexão com o banco: ' . $e->getMessage());
                die('Erro de conexão com o banco de dados. Verifique o arquivo .env.');
            }
        }

        return self::$conn;
    }
}
