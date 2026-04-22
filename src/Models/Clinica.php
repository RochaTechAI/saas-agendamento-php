<?php

namespace Models;

require_once __DIR__ . '/../Config/Database.php';
use Config\Database;
use PDO;

class Clinica {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function buscarPorSlug($slug) {
        $sql = "SELECT * FROM clinicas WHERE slug = :slug AND ativo = 1 LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>