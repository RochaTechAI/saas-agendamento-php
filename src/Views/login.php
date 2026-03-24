<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - MedSaaS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        }
        .btn-login {
            background: #4f46e5;
            color: white;
            font-weight: bold;
            border-radius: 10px;
            padding: 12px;
        }
        .btn-login:hover { background: #4338ca; color:white;}
    </style>
</head>
<body>

<div class="login-card">
    <h2 class="text-center fw-bold mb-4" style="color: #0f172a;">Acesso Restrito</h2>
    
    <?php if(isset($_GET['erro'])): ?>
        <div class="alert alert-danger text-center">E-mail ou senha incorretos!</div>
    <?php endif; ?>

    <form method="POST" action="index.php?acao=logar">
        <div class="mb-3">
            <label class="form-label fw-bold text-secondary">E-mail Corporativo</label>
            <input type="email" name="email" class="form-control form-control-lg bg-light" required placeholder="admin@medsaas.com">
        </div>
        <div class="mb-4">
            <label class="form-label fw-bold text-secondary">Senha</label>
            <input type="password" name="senha" class="form-control form-control-lg bg-light" required placeholder="senha123">
        </div>
        <button type="submit" class="btn btn-login w-100">Entrar no Painel</button>
    </form>
    
    <div class="text-center mt-4">
        <a href="index.php" class="text-decoration-none text-muted">← Voltar para a tela do paciente</a>
    </div>
</div>

</body>
</html>