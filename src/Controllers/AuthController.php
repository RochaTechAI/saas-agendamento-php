<?php

namespace Controllers;

require_once __DIR__ . '/../Models/Usuario.php';
use Models\Usuario;

class AuthController {
    
    // Mostra a tela de Login
    public function login() {
        require_once __DIR__ . '/../Views/login.php';
    }

    // Processa os dados que o usuário digitou
    public function logar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            $usuarioModel = new Usuario();
            $usuarioBanco = $usuarioModel->buscarPorEmail($email);

            // Se achou o e-mail E a senha bater com a criptografia do banco
            if ($usuarioBanco && password_verify($senha, $usuarioBanco['senha'])) {
                // SUCESSO! Ligamos a "pulseira VIP" da sessão
                $_SESSION['usuario_id'] = $usuarioBanco['id'];
                $_SESSION['clinica_id'] = $usuarioBanco['clinica_id'];
                $_SESSION['nome_usuario'] = $usuarioBanco['nome'];
                
                // Redireciona para o painel
                header("Location: index.php?acao=painel");
                exit;
            } else {
                // FALHA! Manda de volta pra tela de login com erro
                header("Location: index.php?acao=login&erro=1");
                exit;
            }
        }
    }

    // Corta a pulseira VIP e sai do sistema
    public function logout() {
        session_destroy();
        header("Location: index.php?acao=login");
        exit;
    }
}
?>