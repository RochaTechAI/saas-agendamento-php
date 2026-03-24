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
        <div class="col-md-3 col-lg-2 sidebar px-3 d-flex flex-column justify-content-between" style="min-height: 100vh;">
            <div>
                <h4 class="text-center mb-4 fw-bold text-white mt-3"><i class="bi bi-hospital"></i> MedSaaS</h4>
                
                <!-- Saudação ao Usuário Logado -->
                <div class="text-center mb-4 pb-3 border-bottom border-secondary">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px; font-size: 1.2rem; font-weight: bold;">
                        <?= strtoupper(substr($_SESSION['nome_usuario'], 0, 1)) ?>
                    </div>
                    <h6 class="text-light mb-0">Olá, <?= htmlspecialchars($_SESSION['nome_usuario']) ?></h6>
                    <small class="text-muted">Administrador</small>
                </div>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php?acao=painel"><i class="bi bi-calendar-check me-2"></i> Agenda do Dia</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php" target="_blank"><i class="bi bi-box-arrow-up-right me-2"></i> Ver Site Público</a>
                    </li>
                </ul>
            </div>
            
            <!-- Botão de Sair no rodapé do menu -->
            <div class="pb-4">
                <a href="index.php?acao=logout" class="btn btn-outline-danger w-100 fw-bold border-0 text-start px-3 text-danger">
                    <i class="bi bi-box-arrow-left me-2"></i> Sair do Sistema
                </a>
            </div>
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
                                        
                                        <!-- ETIQUETA DE STATUS DINÂMICA -->
                                        <td>
                                            <?php if($agendamento['status'] === 'agendado'): ?>
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="bi bi-hourglass-split"></i> Agendado</span>
                                            <?php elseif($agendamento['status'] === 'concluido'): ?>
                                                <span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-check-circle"></i> Concluído</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger rounded-pill px-3 py-2"><i class="bi bi-x-circle"></i> Cancelado</span>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <!-- BOTÕES DE AÇÃO DINÂMICOS -->
                                        <td>
                                            <?php if($agendamento['status'] === 'agendado'): ?>
                                                <!-- Links que chamam a nossa nova rota -->
                                                <a href="index.php?acao=atualizar_status&status=concluido&id=<?= $agendamento['id'] ?>&data=<?= $data_desejada ?>" class="btn btn-sm btn-success fw-bold">
                                                    <i class="bi bi-check2"></i> Concluir
                                                </a>
                                                <a href="index.php?acao=atualizar_status&status=cancelado&id=<?= $agendamento['id'] ?>&data=<?= $data_desejada ?>" class="btn btn-sm btn-outline-danger fw-bold ms-1">
                                                    <i class="bi bi-x-lg"></i> Cancelar
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted small fst-italic"><i class="bi bi-dash"></i> Ação finalizada</span>
                                            <?php endif; ?>
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