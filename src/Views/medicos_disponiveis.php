<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedSaaS - Agendamento</title>
    <!-- Importando o Bootstrap 5 direto da nuvem (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Ícones do Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; } /* Cor de fundo cinza bem clarinho */
    </style>
</head>
<body>

<div class="container py-5">
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4 text-center">
                    <h2 class="text-primary fw-bold mb-4">
                        <i class="bi bi-hospital"></i> Agendamento - MedSaaS
                    </h2>
                    
                    <!-- Formulário para escolher a data -->
                    <form method="GET" action="index.php" class="d-flex justify-content-center align-items-center gap-3">
                        <label for="data" class="fs-5 fw-semibold text-secondary">Escolha a Data:</label>
                        <input type="date" id="data" name="data" value="<?= htmlspecialchars($data_desejada) ?>" class="form-control w-auto">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Lista de Médicos e Horários -->
            <?php if (empty($medicos)): ?>
                <div class="alert alert-danger text-center shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-4 d-block mb-2"></i>
                    <strong>Poxa!</strong> Nenhum médico atende nesta data ou todos os horários estão lotados.
                </div>
            <?php else: ?>
                <?php foreach ($medicos as $medico): ?>
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body p-4">
                            <h4 class="card-title text-dark fw-bold">
                                <i class="bi bi-person-badge text-primary"></i> <?= htmlspecialchars($medico['nome']) ?>
                            </h4>
                            <h6 class="card-subtitle mb-3 text-muted fst-italic">
                                <?= htmlspecialchars($medico['especialidade']) ?>
                            </h6>
                            
                            <hr>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <?php foreach ($medico['horarios_disponiveis'] as $horario): ?>
                                    <!-- Botão de horário usando o padrão 'outline-success' do Bootstrap -->
                                    <button class="btn btn-outline-success fw-bold">
                                        <i class="bi bi-clock"></i> <?= htmlspecialchars($horario) ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>
</div>

<!-- Script do Bootstrap (necessário para alguns componentes funcionarem) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>