<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel da Clínica - MedSaaS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { background-color: #1e293b; min-height: 100vh; color: white; padding-top: 20px; }
        .nav-link { color: #cbd5e1; font-weight: 500; margin-bottom: 10px; border-radius: 8px; }
        .nav-link:hover, .nav-link.active { background-color: #334155; color: white; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .table th { background-color: #f1f5f9; color: #475569; font-weight: 600; border-bottom: 2px solid #e2e8f0; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Menu Lateral (Sidebar) -->
        <div class="col-md-3 col-lg-2 sidebar px-3">
            <h4 class="text-center mb-4 fw-bold text-white"><i class="bi bi-hospital"></i> MedSaaS</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php?acao=painel"><i class="bi bi-calendar-check me-2"></i> Agenda do Dia</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php"><i class="bi bi-box-arrow-up-right me-2"></i> Ver Site Público</a>
                </li>
            </ul>
        </div>

        <!-- Conteúdo Principal -->
        <div class="col-md-9 col-lg-10 p-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark">Agenda de Consultas</h2>
                
                <!-- Filtro de Data do Painel -->
                <form method="GET" action="index.php" class="d-flex gap-2">
                    <input type="hidden" name="acao" value="painel">
                    <input type="date" name="data" class="form-control" value="<?= htmlspecialchars($data_desejada) ?>">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </form>
            </div>

            <div class="card p-4">
                <?php if (empty($agendamentos)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1"></i>
                        <h5 class="mt-3">Nenhum paciente agendado para este dia.</h5>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Horário</th>
                                    <th>Nome do Paciente</th>
                                    <th>Médico Responsável</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($agendamentos as $agendamento): ?>
                                    <tr>
                                        <td><span class="badge bg-light text-dark border fs-6"><i class="bi bi-clock"></i> <?= substr($agendamento['hora_inicio'], 0, 5) ?></span></td>
                                        <td class="fw-bold"><?= htmlspecialchars($agendamento['paciente_nome']) ?></td>
                                        <td class="text-muted"><i class="bi bi-person-badge"></i> <?= htmlspecialchars($agendamento['medico_nome']) ?></td>
                                        <td>
                                            <span class="badge bg-warning text-dark rounded-pill">Agendado</span>
                                        </td>
                                        <td>
                                            <!-- Botões para o futuro (Concluir e Cancelar) -->
                                            <button class="btn btn-sm btn-success disabled"><i class="bi bi-check2"></i> Concluir</button>
                                            <button class="btn btn-sm btn-danger disabled"><i class="bi bi-x-lg"></i> Cancelar</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>