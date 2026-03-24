<?php

namespace Models;

require_once __DIR__ . '/../Config/Database.php';
use Config\Database;
use PDO;

class Usuario {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Busca o usuário pelo E-mail para podermos verificar a senha
    public function buscarPorEmail($email) {
        $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC); // Retorna os dados do usuário ou Falso se não achar
    }
}
?>