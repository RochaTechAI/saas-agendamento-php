<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedSaaS - Agendamento Premium</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        /* CSS Customizado para dar o toque 'Premium' */
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        .header-gradient {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 20px rgba(13, 110, 253, 0.15);
        }

        .doctor-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }
        .doctor-card:hover { transform: translateY(-5px); }

        .avatar-img {
            width: 80px; height: 80px;
            border-radius: 50%;
            border: 3px solid #e9ecef;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        /* Animação e design dos botões de horário */
        .time-slot-btn {
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            background-color: white;
            color: #0d6efd;
            border: 2px solid #e9ecef;
        }
        .time-slot-btn:hover {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }
    </style>
</head>
<body>

<div class="container py-5 max-w-4xl">
    
    <!-- Mensagem de Sucesso animada (Aparece quando marca a consulta) -->
    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success alert-dismissible fade show text-center shadow-sm rounded-4 mb-4" role="alert">
            <i class="bi bi-check-circle-fill fs-4 d-block mb-1"></i>
            <strong>Consulta Agendada com Sucesso!</strong> O horário foi reservado.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Cabeçalho Moderno -->
    <div class="header-gradient mb-5 text-center mx-auto" style="max-width: 800px;">
        <h2 class="fw-bold mb-3"><i class="bi bi-calendar2-heart"></i> Agendamento MedSaaS</h2>
        
        <form method="GET" action="index.php" class="d-flex justify-content-center align-items-center gap-3 bg-white p-3 rounded-4 shadow-sm mx-auto" style="max-width: 500px;">
            <input type="date" id="data" name="data" value="<?= htmlspecialchars($data_desejada) ?>" class="form-control border-0 fw-bold text-secondary" style="background: #f8f9fa;">
            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                Buscar
            </button>
        </form>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <?php if (empty($medicos)): ?>
                <div class="alert bg-white border-0 shadow-sm text-center rounded-4 p-5">
                    <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                    <h4 class="mt-3 text-secondary fw-bold">Nenhum horário disponível</h4>
                    <p class="text-muted">Tente buscar por uma data diferente.</p>
                </div>
            <?php else: ?>
                <?php foreach ($medicos as $medico): ?>
                    <!-- Card do Médico Premium -->
                    <div class="card doctor-card mb-4 bg-white">
                        <div class="card-body p-4">
                            
                            <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                                <!-- API gratuita que gera um Avatar com as iniciais do nome do médico -->
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($medico['nome']) ?>&background=0d6efd&color=fff&size=100" alt="Avatar" class="avatar-img me-4">
                                <div>
                                    <h4 class="fw-bold text-dark mb-1"><?= htmlspecialchars($medico['nome']) ?></h4>
                                    <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill fw-semibold">
                                        <i class="bi bi-stethoscope"></i> <?= htmlspecialchars($medico['especialidade']) ?>
                                    </span>
                                </div>
                            </div>
                            
                            <p class="text-muted fw-bold mb-3 small text-uppercase spacing"><i class="bi bi-clock-history"></i> Horários Livres:</p>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <?php foreach ($medico['horarios_disponiveis'] as $horario): ?>
                                    
                                    <!-- A MÁGICA: Agora cada botão é um mini formulário que envia os dados pro Banco! -->
                                    <form method="POST" action="index.php?acao=agendar" class="m-0">
                                        <input type="hidden" name="medico_id" value="<?= $medico['id'] ?>">
                                        <input type="hidden" name="data_consulta" value="<?= htmlspecialchars($data_desejada) ?>">
                                        <input type="hidden" name="hora_inicio" value="<?= htmlspecialchars($horario) ?>">
                                        
                                        <button type="submit" class="time-slot-btn" title="Clique para agendar">
                                            <?= htmlspecialchars($horario) ?>
                                        </button>
                                    </form>

                                <?php endforeach; ?>
                            </div>
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