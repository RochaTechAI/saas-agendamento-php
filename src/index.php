<?php

// Importamos a classe que acabamos de criar
require_once __DIR__ . '/Config/Database.php';

use Config\Database;

echo "<h1>Testando o MedSaaS... 🏥</h1>";

// Tentamos pegar a conexão
$db = Database::getConnection();

if ($db) {
    echo "<h3 style='color: green;'>✅ Sucesso! O PHP conseguiu se conectar ao MySQL do Docker!</h3>";
} else {
    echo "<h3 style='color: red;'>❌ Falha na conexão.</h3>";
}

?>