<?php

namespace Controllers;

require_once __DIR__ . '/../Models/Clinica.php';
use Models\Clinica;

class MedicoController {
    
    public function listarDisponibilidade() {
        // Pega o 'slug' da URL (Ex: ?c=vida-saudavel). Se não vier, assume a Vida Saudável para não quebrar testes diretos
        $slug = $_GET['c'] ?? 'vida-saudavel';

        $clinicaModel = new Clinica();
        $clinica = $clinicaModel->buscarPorSlug($slug);

        // Se tentarem acessar um link de clínica que não existe, bloqueia!
        if (!$clinica) {
            die("<body style='background:#020812; color:#fff; text-align:center; padding:100px; font-family:sans-serif;'>
                <h1>🏥 Clínica não encontrada</h1>
                <p style='color:#7aa0c0'>Verifique o link de acesso fornecido pelo seu médico.</p>
                </body>");
        }

        // Variáveis que vamos enviar para a Tela!
        $clinica_id = $clinica['id'];
        $clinica_nome = $clinica['nome'];
        $clinica_slug = $clinica['slug'];

        require_once __DIR__ . '/../Views/medicos_disponiveis.php';
    }
}
?>