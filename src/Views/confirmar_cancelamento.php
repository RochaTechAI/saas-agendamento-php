<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancelar Consulta — MedSaaS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #020812; color: #fff; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        body::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse at 50% -20%, rgba(239, 68, 68, 0.15) 0%, transparent 70%); pointer-events: none; z-index: -1; }
        .glass-card { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 24px; padding: 40px; max-width: 500px; width: 100%; text-align: center; backdrop-filter: blur(16px); box-shadow: 0 20px 50px rgba(0,0,0,0.5); }
        .warning-icon { width: 80px; height: 80px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 20px; box-shadow: 0 0 30px rgba(239, 68, 68, 0.2); }
        .info-box { background: rgba(0, 0, 0, 0.4); border: 1px solid rgba(255, 255, 255, 0.05); padding: 20px; border-radius: 16px; margin-bottom: 30px; text-align: left; }
        .btn-cancelar { background: linear-gradient(135deg, #ef4444, #b91c1c); color: white; border: none; padding: 14px; border-radius: 12px; font-weight: 700; width: 100%; margin-bottom: 12px; transition: all 0.2s; box-shadow: 0 10px 20px rgba(239, 68, 68, 0.3); }
        .btn-cancelar:hover { transform: translateY(-2px); box-shadow: 0 12px 25px rgba(239, 68, 68, 0.4); color: white; }
        .btn-voltar { background: transparent; border: 1px solid rgba(255,255,255,0.1); color: #7aa0c0; padding: 14px; border-radius: 12px; font-weight: 600; width: 100%; text-decoration: none; display: inline-block; transition: all 0.2s; }
        .btn-voltar:hover { background: rgba(255,255,255,0.05); color: #fff; }
    </style>
</head>
<body>

    <div class="glass-card">
        <div class="warning-icon"><i class="bi bi-exclamation-triangle"></i></div>
        <h2 class="fw-bold mb-3">Cancelar Agendamento?</h2>
        <p style="color: #7aa0c0; margin-bottom: 24px;">Tem certeza que deseja cancelar a sua consulta abaixo? Essa ação não pode ser desfeita.</p>

        <!-- Informações do Banco injetadas aqui -->
        <div class="info-box">
            <div style="margin-bottom: 10px;"><i class="bi bi-person-badge text-primary me-2"></i> <strong class="text-white"><?= htmlspecialchars($consulta['medico_nome']) ?></strong></div>
            <div><i class="bi bi-calendar3 text-primary me-2"></i> <span class="text-light"><?= date('d/m/Y', strtotime($consulta['data_consulta'])) ?> às <?= substr($consulta['hora_inicio'], 0, 5) ?></span></div>
        </div>

        <!-- O POST QUE CANCELA DE VERDADE -->
        <form method="POST" action="index.php?acao=efetivar_cancelamento">
            <!-- Manda o Token escondido pro servidor -->
            <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token']) ?>">
            
            <button type="submit" class="btn-cancelar">Sim, quero cancelar a consulta</button>
            <a href="index.php" class="btn-voltar">Não, manter meu horário</a>
        </form>
    </div>

</body>
</html>