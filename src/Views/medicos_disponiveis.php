<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedSaaS - Agendamento Premium</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
        }
        
        /* A MÁGICA DO FUNDO DO SITE AQUI */
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            /* Colocamos uma película escura (rgba) por cima da imagem para o site não ficar bagunçado */
            background-image: linear-gradient(rgba(15, 23, 42, 0.75), rgba(15, 23, 42, 0.85)), 
                              url('https://images.unsplash.com/photo-1551076805-e1869033e561?auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed; /* O fundo fica parado quando você rola a página */
            min-height: 100vh;
        }

        /* Efeito de Vidro (Glassmorphism) no Cabeçalho */
        .glass-header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            box-shadow: 0 30px 50px rgba(0,0,0,0.3);
            color: white;
        }

        /* O Cartão do Médico Flutuante */
        .doctor-card {
            background: #ffffff;
            border-radius: 24px;
            border: none;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            margin-top: 20px;
        }

        /* Botões de Horário */
        .time-slot-btn {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            color: #334155;
            border-radius: 12px;
            padding: 12px 15px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            width: 100%;
        }
        .time-slot-btn:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: scale(1.05) translateY(-2px);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
        }

        /* Campo de Data Estilizado */
        .date-input {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 12px;
            padding: 12px 20px;
            font-weight: 600;
            color: #334155;
        }
        .date-input:focus {
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.3);
            outline: none;
        }
        
        /* Botão de Busca */
        .btn-search {
            background: var(--primary);
            border: none;
            border-radius: 12px;
            padding: 12px 30px;
            font-weight: 700;
            transition: all 0.3s;
        }
        .btn-search:hover {
            background: var(--primary-hover);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="py-5">

<div class="container max-w-4xl" style="max-width: 900px;">
    
    <!-- Alerta de Sucesso -->
    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-lg border-0 rounded-4 mb-5 p-4" style="background: #ecfdf5; color: #065f46;" role="alert">
            <i class="bi bi-check-circle-fill fs-1 me-3 text-success"></i>
            <div>
                <h4 class="alert-heading fw-bold mb-1">Fantástico! Consulta Confirmada.</h4>
                <p class="mb-0">O horário foi reservado com sucesso no banco de dados.</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Cabeçalho Glassmorphism -->
    <div class="glass-header p-5 mb-5 text-center">
        <h1 class="mb-4" style="font-weight: 800; letter-spacing: -1px; font-size: 2.5rem;">
            <i class="bi bi-heart-pulse-fill text-danger"></i> MedSaaS <span style="color: #60a5fa;">Premium</span>
        </h1>
        <p class="mb-4 fs-5 text-light opacity-75">Agende sua consulta de forma rápida e segura.</p>
        
        <form method="GET" action="index.php" class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-3 mx-auto" style="max-width: 600px;">
            <div class="input-group shadow-lg rounded-4 p-1" style="background: rgba(255,255,255,0.2);">
                <span class="input-group-text bg-transparent border-0 ps-4 text-white"><i class="bi bi-calendar-event-fill fs-5"></i></span>
                <input type="date" id="data" name="data" value="<?= htmlspecialchars($data_desejada) ?>" class="form-control date-input me-2">
                <button type="submit" class="btn btn-search text-white px-5 rounded-4 shadow-none">
                    Buscar
                </button>
            </div>
        </form>
    </div>

    <!-- Resultados -->
    <div class="row justify-content-center">
        <div class="col-12">
            
            <?php if (empty($medicos)): ?>
                <!-- Estado vazio adaptado para fundo escuro -->
                <div class="text-center p-5 rounded-4 border-0" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                    <i class="bi bi-calendar-x text-white opacity-50" style="font-size: 5rem;"></i>
                    <h3 class="fw-bold text-white mt-3">Nenhum horário livre</h3>
                    <p class="text-white opacity-75 fs-5">Não há médicos disponíveis nesta data. Tente buscar outro dia.</p>
                </div>
            <?php else: ?>
                <?php foreach ($medicos as $medico): ?>
                    <!-- Painel do Médico (Clean e Moderno) -->
                    <div class="card doctor-card p-4 p-md-5">
                        
                        <div class="d-flex flex-column align-items-center text-center mb-4 pb-4 border-bottom">
                            <h2 class="fw-bolder mb-2" style="color: #0f172a; font-size: 2.2rem;"><?= htmlspecialchars($medico['nome']) ?></h2>
                            <div class="badge px-4 py-2 rounded-pill" style="background: #e0e7ff; color: var(--primary); font-size: 1.1rem; font-weight: 600;">
                                <i class="bi bi-clipboard2-pulse me-1"></i> <?= htmlspecialchars($medico['especialidade']) ?>
                            </div>
                        </div>
                        
                        <h5 class="fw-bold mb-4 text-center" style="color: #475569;"><i class="bi bi-clock-history me-2 text-primary"></i> Selecione um horário para agendar:</h5>
                        
                        <div class="row g-3 justify-content-center">
                            <?php foreach ($medico['horarios_disponiveis'] as $horario): ?>
                                <div class="col-6 col-md-3">
                                    <form method="POST" action="index.php?acao=agendar" class="m-0">
                                        <input type="hidden" name="medico_id" value="<?= $medico['id'] ?>">
                                        <input type="hidden" name="data_consulta" value="<?= htmlspecialchars($data_desejada) ?>">
                                        <input type="hidden" name="hora_inicio" value="<?= htmlspecialchars($horario) ?>">
                                        
                                        <button type="submit" class="time-slot-btn">
                                            <?= htmlspecialchars($horario) ?>
                                        </button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>