<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipe Médica — MedSaaS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --cyan:  #00d4ff;
        --blue:  #0066ff;
        --dark:  #020812;
        --dark2: #030f22;
        --glass: rgba(255,255,255,0.028);
        --glass-b: rgba(0,212,255,0.09);
        --sb-w: 248px;
    }

    html { height: 100%; }

    body {
        font-family: 'Inter', sans-serif; background: var(--dark); color: #e8f0ff; min-height: 100vh; display: flex; overflow-x: hidden;
    }
    body::before {
        content: ''; position: fixed; inset: 0;
        background: radial-gradient(ellipse 70% 50% at 50% 0%, rgba(0,80,200,0.14) 0%, transparent 65%),
                    radial-gradient(ellipse 40% 30% at 90% 80%, rgba(0,200,255,0.06) 0%, transparent 55%),
                    linear-gradient(180deg, #020812 0%, #02091a 100%);
        pointer-events: none; z-index: 0;
    }
    body::after {
        content: ''; position: fixed; bottom: -10%; left: -20%; right: -20%; height: 45%;
        background: linear-gradient(90deg, rgba(0,212,255,0.04) 1px, transparent 1px) 0 0 / 72px 72px,
                    linear-gradient(rgba(0,212,255,0.04) 1px, transparent 1px) 0 0 / 72px 72px;
        transform: perspective(300px) rotateX(56deg); transform-origin: 50% 100%;
        mask-image: linear-gradient(0deg, rgba(0,0,0,0.5) 0%, transparent 70%); -webkit-mask-image: linear-gradient(0deg, rgba(0,0,0,0.5) 0%, transparent 70%);
        pointer-events: none; z-index: 0;
    }

    /* SIDEBAR */
    .sidebar { width: var(--sb-w); min-height: 100vh; position: fixed; top: 0; left: 0; bottom: 0; background: rgba(2,8,18,0.96); border-right: 1px solid rgba(0,212,255,0.06); display: flex; flex-direction: column; z-index: 100; backdrop-filter: blur(12px); }
    .sidebar::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, transparent 0%, var(--cyan) 40%, var(--blue) 70%, transparent 100%); opacity: 0.5; }
    .sb-brand { padding: 24px 20px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid rgba(0,212,255,0.05); }
    .sb-logo { width: 38px; height: 38px; background: linear-gradient(135deg, var(--blue), var(--cyan)); border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 17px; color: #fff; flex-shrink: 0; box-shadow: 0 4px 16px rgba(0,102,255,0.4), 0 0 0 1px rgba(0,212,255,0.2); }
    .sb-brand-name { font-size: 1rem; font-weight: 800; color: #fff; letter-spacing: -0.2px; }
    .sb-brand-sub { font-size: 0.62rem; color: #1a3050; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; }
    .sb-nav { flex: 1; padding: 20px 12px; overflow-y: auto; }
    .sb-section { font-size: 0.6rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.12em; color: #0e2035; padding: 0 8px; margin-bottom: 8px; margin-top: 16px; }
    .nav-item { display: flex; align-items: center; gap: 10px; padding: 9px 10px; border-radius: 10px; color: #1e3a58; text-decoration: none; font-size: 0.84rem; font-weight: 600; transition: all 0.18s; margin-bottom: 2px; position: relative; }
    .nav-item i { font-size: 0.95rem; width: 17px; text-align: center; }
    .nav-item:hover { background: rgba(0,212,255,0.05); color: #5090b0; }
    .nav-item.active { background: rgba(0,102,255,0.12); color: var(--cyan); border: 1px solid rgba(0,212,255,0.12); }
    .nav-item.active::before { content: ''; position: absolute; left: -12px; top: 50%; transform: translateY(-50%); width: 3px; height: 18px; background: var(--cyan); border-radius: 3px; }
    .sb-footer { padding: 16px 12px; border-top: 1px solid rgba(0,212,255,0.05); }
    .user-row { display: flex; align-items: center; gap: 10px; padding: 10px; border-radius: 10px; margin-bottom: 6px; }
    .user-ava { width: 34px; height: 34px; border-radius: 9px; background: linear-gradient(135deg, var(--blue), var(--cyan)); display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 800; color: #fff; flex-shrink: 0; box-shadow: 0 3px 12px rgba(0,102,255,0.35), inset 0 1px 0 rgba(255,255,255,0.1); }
    .user-name { font-size: 0.8rem; font-weight: 700; color: #c0d8f0; }
    .user-role { font-size: 0.65rem; color: #0e2035; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; }
    .btn-logout { display: flex; align-items: center; gap: 8px; padding: 9px 10px; border-radius: 10px; color: #2a3a50; text-decoration: none; font-size: 0.8rem; font-weight: 600; transition: all 0.18s; width: 100%; }
    .btn-logout:hover { background: rgba(239,68,68,0.08); color: #f87171; }

    /* MAIN CONTENT */
    .main { margin-left: var(--sb-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; position: relative; z-index: 1; }
    .topbar { height: 64px; background: rgba(2,8,18,0.85); border-bottom: 1px solid rgba(0,212,255,0.06); padding: 0 32px; display: flex; align-items: center; justify-content: space-between; backdrop-filter: blur(12px); position: sticky; top: 0; z-index: 50; }
    .topbar-title h1 { font-size: 0.95rem; font-weight: 700; color: #c0d8f0; letter-spacing: -0.2px; }
    .topbar-title p { font-size: 0.7rem; color: #0e2035; margin-top: 1px; font-weight: 600; }
    .page-body { padding: 28px 32px 48px; }

    /* LAYOUT MÉDICOS */
    .medicos-grid { display: grid; grid-template-columns: 360px 1fr; gap: 24px; }
    @media (max-width: 900px) { .medicos-grid { grid-template-columns: 1fr; } .sidebar { display: none; } .main { margin-left: 0; } }

    /* CARDS */
    .content-card { background: var(--glass); border: 1px solid var(--glass-b); border-radius: 20px; padding: 24px; backdrop-filter: blur(12px); box-shadow: 0 20px 60px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.04); position: relative; overflow: hidden;}
    .content-card::before { content: ''; display: block; position: absolute; top:0; left:0; right:0; height: 2px; background: linear-gradient(90deg, transparent 5%, rgba(0,212,255,0.35) 40%, rgba(0,102,255,0.25) 65%, transparent 95%); }
    .card-title { font-size: 0.88rem; font-weight: 700; color: #c0d8f0; display: flex; align-items: center; gap: 8px; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid rgba(0,212,255,0.05);}
    .card-title i { color: var(--cyan); }

    /* FORMULÁRIO DARK */
    .dark-label { font-size: 0.75rem; font-weight: 600; color: #5090b0; margin-bottom: 6px; display: block; }
    .dark-input { width: 100%; background: rgba(255,255,255,0.03); border: 1px solid rgba(0,212,255,0.1); color: #c0d8f0; border-radius: 10px; padding: 10px 14px; font-size: 0.85rem; font-family: 'Inter', sans-serif; transition: all 0.2s; outline: none; margin-bottom: 18px;}
    .dark-input:focus { border-color: rgba(0,212,255,0.3); box-shadow: 0 0 0 3px rgba(0,212,255,0.07); color: #fff; background: rgba(255,255,255,0.06);}
    select.dark-input option { background: var(--dark2); color: #fff; }

    /* CHECKBOX DOS DIAS DA SEMANA (Estilo Tags) */
    .days-grid { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 18px; }
    .day-checkbox input { display: none; }
    .day-checkbox span { background: rgba(255,255,255,0.03); border: 1px solid rgba(0,212,255,0.1); color: #7aa0c0; padding: 6px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 600; cursor: pointer; transition: all 0.2s; display: inline-block;}
    .day-checkbox input:checked + span { background: rgba(0,212,255,0.15); border-color: var(--cyan); color: #fff; box-shadow: 0 0 10px rgba(0,212,255,0.2); }

    .btn-submit { background: linear-gradient(135deg, var(--blue), #0095cc); color: #fff; border: none; border-radius: 10px; padding: 12px; width: 100%; font-size: 0.85rem; font-weight: 700; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 16px rgba(0,102,255,0.35); margin-top: 10px;}
    .btn-submit:hover { box-shadow: 0 6px 24px rgba(0,102,255,0.5); transform: translateY(-1px); }

    /* TABELA DARK */
    table { width: 100%; border-collapse: collapse; }
    thead th { background: rgba(0,0,0,0.2); color: #0e2035; font-size: 0.64rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; padding: 11px 20px; text-align: left; border-bottom: 1px solid rgba(0,212,255,0.04); }
    tbody td { padding: 15px 20px; border-bottom: 1px solid rgba(0,212,255,0.03); font-size: 0.85rem; vertical-align: middle; }
    tbody tr:hover { background: rgba(0,212,255,0.025); }
    .time-pill { display: inline-flex; align-items: center; background: rgba(0,212,255,0.06); border: 1px solid rgba(0,212,255,0.12); color: var(--cyan); border-radius: 8px; padding: 4px 10px; font-weight: 800; font-size: 0.8rem; }
    .doc-name { color: #c0d8f0; font-weight: 700; display: flex; align-items: center; gap: 8px; }
    .doc-name i { color: var(--cyan); }
    .spec-chip { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #7aa0c0; font-size: 0.7rem; font-weight: 600; padding: 4px 12px; border-radius: 20px; }

    /* ALERTA DE SUCESSO */
    .alert-success { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2); color: #6ee7b7; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 10px;}
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sb-brand">
        <div class="sb-logo"><i class="bi bi-hospital-fill"></i></div>
        <div>
            <div class="sb-brand-name">MedSaaS</div>
            <div class="sb-brand-sub">Painel Clínico</div>
        </div>
    </div>

    <nav class="sb-nav">
        <div class="sb-section">Principal</div>
        <a href="index.php?acao=painel" class="nav-item">
            <i class="bi bi-calendar2-heart"></i> Agenda do Dia
        </a>
        <a href="index.php?acao=painel_medicos" class="nav-item active">
            <i class="bi bi-people"></i> Equipe Médica
        </a>
        <a href="index.php" target="_blank" class="nav-item">
            <i class="bi bi-globe2"></i> Site Público
        </a>
    </nav>

    <div class="sb-footer">
        <div class="user-row">
            <div class="user-ava"><?= strtoupper(substr($_SESSION['nome_usuario'], 0, 1)) ?></div>
            <div>
                <div class="user-name"><?= htmlspecialchars($_SESSION['nome_usuario']) ?></div>
                <div class="user-role">Administrador</div>
            </div>
        </div>
        <a href="index.php?acao=logout" class="btn-logout">
            <i class="bi bi-box-arrow-left"></i> Sair da conta
        </a>
    </div>
</aside>

<main class="main">
    <header class="topbar">
        <div class="topbar-title">
            <h1>Gestão da Equipe</h1>
            <p>Cadastre doutores e personalize os dias de atendimento</p>
        </div>
    </header>

    <div class="page-body">
        <?php if (isset($_GET['sucesso'])): ?>
            <div class="alert-success">
                <i class="bi bi-check-circle-fill fs-5"></i> 
                Médico cadastrado com sucesso! A agenda dele foi gerada nos dias selecionados.
            </div>
        <?php endif; ?>

        <div class="medicos-grid">
            
            <!-- FORMULÁRIO COMPLETO -->
            <div class="content-card">
                <div class="card-title">
                    <i class="bi bi-person-plus-fill"></i> Novo Médico
                </div>
                
                <form method="POST" action="index.php?acao=salvar_medico">
                    <label class="dark-label">Nome do Doutor(a)</label>
                    <input type="text" name="nome" class="dark-input" required placeholder="Ex: Dra. Ana Costa">
                    
                    <label class="dark-label">Especialidade</label>
                    <input type="text" name="especialidade" class="dark-input" required placeholder="Ex: Pediatria">
                    
                    <!-- ESCOLHA DE DIAS DA SEMANA (CHECKBOXES ESTILIZADOS) -->
                    <label class="dark-label"><i class="bi bi-calendar-week"></i> Dias de Atendimento</label>
                    <div class="days-grid">
                        <label class="day-checkbox">
                            <!-- 1=Segunda ... 6=Sábado, 0=Domingo -->
                            <input type="checkbox" name="dias_semana[]" value="1" checked> <span>Seg</span>
                        </label>
                        <label class="day-checkbox">
                            <input type="checkbox" name="dias_semana[]" value="2" checked> <span>Ter</span>
                        </label>
                        <label class="day-checkbox">
                            <input type="checkbox" name="dias_semana[]" value="3" checked> <span>Qua</span>
                        </label>
                        <label class="day-checkbox">
                            <input type="checkbox" name="dias_semana[]" value="4" checked> <span>Qui</span>
                        </label>
                        <label class="day-checkbox">
                            <input type="checkbox" name="dias_semana[]" value="5" checked> <span>Sex</span>
                        </label>
                        <label class="day-checkbox">
                            <input type="checkbox" name="dias_semana[]" value="6"> <span>Sáb</span>
                        </label>
                        <label class="day-checkbox">
                            <input type="checkbox" name="dias_semana[]" value="0"> <span>Dom</span>
                        </label>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label class="dark-label"><i class="bi bi-clock"></i> Início</label>
                            <input type="time" name="hora_inicio" class="dark-input" value="08:00" required>
                        </div>
                        <div>
                            <label class="dark-label"><i class="bi bi-clock-fill"></i> Fim</label>
                            <input type="time" name="hora_fim" class="dark-input" value="18:00" required>
                        </div>
                    </div>

                    <label class="dark-label">Duração da Consulta</label>
                    <select name="tempo_consulta" class="dark-input">
                        <option value="15">15 minutos</option>
                        <option value="20">20 minutos</option>
                        <option value="30" selected>30 minutos</option>
                        <option value="60">1 Hora</option>
                    </select>

                    <button type="submit" class="btn-submit">
                        <i class="bi bi-plus-circle me-1"></i> Cadastrar Médico
                    </button>
                </form>
            </div>

            <!-- TABELA DE MÉDICOS -->
            <div class="content-card" style="padding: 0;">
                <div class="card-title" style="padding: 24px 24px 16px; margin: 0;">
                    <i class="bi bi-people-fill"></i> Equipe Atual
                </div>
                
                <?php if (empty($medicos)): ?>
                    <div style="text-align: center; padding: 40px; color: #5090b0;">Nenhum médico cadastrado.</div>
                <?php else: ?>
                    <div style="overflow-x: auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Médico</th>
                                    <th>Especialidade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($medicos as $m): ?>
                                <tr>
                                    <td><span class="time-pill">#<?= $m['id'] ?></span></td>
                                    <td>
                                        <div class="doc-name">
                                            <i class="bi bi-person-badge"></i> <?= htmlspecialchars($m['nome']) ?>
                                        </div>
                                    </td>
                                    <td><span class="spec-chip"><?= htmlspecialchars($m['especialidade']) ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</main>

</body>
</html>