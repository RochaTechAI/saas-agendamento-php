<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel — MedSaaS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --cyan:  #00d4ff;
        --blue:  #0066ff;
        --dark:  #020812;
        --dark2: #030f22;
        --panel: rgba(3,12,28,0.96);
        --glass: rgba(255,255,255,0.028);
        --glass-b: rgba(0,212,255,0.09);
        --sb-w: 248px;
    }

    html { height: 100%; }

    body {
        font-family: 'Inter', sans-serif;
        background: var(--dark);
        color: #e8f0ff;
        min-height: 100vh;
        display: flex;
        overflow-x: hidden;
    }

    /* ══════════════════════════════════════
       BACKGROUND
    ══════════════════════════════════════ */
    body::before {
        content: '';
        position: fixed;
        inset: 0;
        background:
            radial-gradient(ellipse 70% 50% at 50% 0%,   rgba(0,80,200,0.14)  0%, transparent 65%),
            radial-gradient(ellipse 40% 30% at 90% 80%,  rgba(0,200,255,0.06) 0%, transparent 55%),
            linear-gradient(180deg, #020812 0%, #02091a 100%);
        pointer-events: none;
        z-index: 0;
    }

    /* Perspective floor on the background */
    body::after {
        content: '';
        position: fixed;
        bottom: -10%;
        left: -20%; right: -20%;
        height: 45%;
        background:
            linear-gradient(90deg,  rgba(0,212,255,0.04) 1px, transparent 1px) 0 0 / 72px 72px,
            linear-gradient(         rgba(0,212,255,0.04) 1px, transparent 1px) 0 0 / 72px 72px;
        transform: perspective(300px) rotateX(56deg);
        transform-origin: 50% 100%;
        mask-image: linear-gradient(0deg, rgba(0,0,0,0.5) 0%, transparent 70%);
        -webkit-mask-image: linear-gradient(0deg, rgba(0,0,0,0.5) 0%, transparent 70%);
        pointer-events: none;
        z-index: 0;
    }

    /* ══════════════════════════════════════
       SIDEBAR
    ══════════════════════════════════════ */
    .sidebar {
        width: var(--sb-w);
        min-height: 100vh;
        position: fixed;
        top: 0; left: 0; bottom: 0;
        background: rgba(2,8,18,0.96);
        border-right: 1px solid rgba(0,212,255,0.06);
        display: flex;
        flex-direction: column;
        z-index: 100;
        backdrop-filter: blur(12px);
    }

    /* Glowing top accent */
    .sidebar::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, transparent 0%, var(--cyan) 40%, var(--blue) 70%, transparent 100%);
        opacity: 0.5;
    }

    .sb-brand {
        padding: 24px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 1px solid rgba(0,212,255,0.05);
    }

    .sb-logo {
        width: 38px; height: 38px;
        background: linear-gradient(135deg, var(--blue), var(--cyan));
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        color: #fff;
        flex-shrink: 0;
        box-shadow: 0 4px 16px rgba(0,102,255,0.4), 0 0 0 1px rgba(0,212,255,0.2);
    }

    .sb-brand-name {
        font-size: 1rem;
        font-weight: 800;
        color: #fff;
        letter-spacing: -0.2px;
    }

    .sb-brand-sub {
        font-size: 0.62rem;
        color: #1a3050;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .sb-nav {
        flex: 1;
        padding: 20px 12px;
        overflow-y: auto;
    }

    .sb-section {
        font-size: 0.6rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #0e2035;
        padding: 0 8px;
        margin-bottom: 8px;
        margin-top: 16px;
    }

    .sb-section:first-child { margin-top: 0; }

    .nav-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 10px;
        border-radius: 10px;
        color: #1e3a58;
        text-decoration: none;
        font-size: 0.84rem;
        font-weight: 600;
        transition: all 0.18s;
        margin-bottom: 2px;
        position: relative;
    }

    .nav-item i { font-size: 0.95rem; width: 17px; text-align: center; }
    .nav-item:hover { background: rgba(0,212,255,0.05); color: #5090b0; }

    .nav-item.active {
        background: rgba(0,102,255,0.12);
        color: var(--cyan);
        border: 1px solid rgba(0,212,255,0.12);
    }

    /* Active dot */
    .nav-item.active::before {
        content: '';
        position: absolute;
        left: -12px; top: 50%;
        transform: translateY(-50%);
        width: 3px; height: 18px;
        background: var(--cyan);
        border-radius: 3px;
    }

    .sb-footer {
        padding: 16px 12px;
        border-top: 1px solid rgba(0,212,255,0.05);
    }

    .user-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        border-radius: 10px;
        margin-bottom: 6px;
    }

    .user-ava {
        width: 34px; height: 34px;
        border-radius: 9px;
        background: linear-gradient(135deg, var(--blue), var(--cyan));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 800;
        color: #fff;
        flex-shrink: 0;
        box-shadow: 0 3px 12px rgba(0,102,255,0.35), inset 0 1px 0 rgba(255,255,255,0.1);
    }

    .user-name {
        font-size: 0.8rem;
        font-weight: 700;
        color: #c0d8f0;
    }

    .user-role {
        font-size: 0.65rem;
        color: #0e2035;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .btn-logout {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 9px 10px;
        border-radius: 10px;
        color: #2a3a50;
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.18s;
        width: 100%;
    }

    .btn-logout:hover { background: rgba(239,68,68,0.08); color: #f87171; }

    /* ══════════════════════════════════════
       MAIN CONTENT
    ══════════════════════════════════════ */
    .main {
        margin-left: var(--sb-w);
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        position: relative;
        z-index: 1;
    }

    /* TOP BAR */
    .topbar {
        height: 64px;
        background: rgba(2,8,18,0.85);
        border-bottom: 1px solid rgba(0,212,255,0.06);
        padding: 0 32px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        backdrop-filter: blur(12px);
        position: sticky;
        top: 0;
        z-index: 50;
    }

    .topbar-title h1 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #c0d8f0;
        letter-spacing: -0.2px;
    }

    .topbar-title p {
        font-size: 0.7rem;
        color: #0e2035;
        margin-top: 1px;
        font-weight: 600;
    }

    .date-form {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .date-form input[type="date"] {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(0,212,255,0.1);
        border-radius: 10px;
        padding: 8px 14px;
        font-size: 0.8rem;
        font-family: 'Inter', sans-serif;
        color: #7aa0c0;
        outline: none;
        cursor: pointer;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .date-form input:focus {
        border-color: rgba(0,212,255,0.3);
        box-shadow: 0 0 0 3px rgba(0,212,255,0.07);
    }

    .btn-filter {
        background: linear-gradient(135deg, var(--blue), #0095cc);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 8px 18px;
        font-size: 0.8rem;
        font-weight: 700;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 16px rgba(0,102,255,0.35);
    }

    .btn-filter:hover { box-shadow: 0 6px 24px rgba(0,102,255,0.5); transform: translateY(-1px); }

    /* PAGE BODY */
    .page-body { padding: 28px 32px 48px; }

    /* ── STATS GRID — 3D cards ─────────────── */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: var(--glass);
        border: 1px solid var(--glass-b);
        border-radius: 18px;
        padding: 20px 20px 18px;
        backdrop-filter: blur(12px);
        box-shadow:
            0 0 0 1px rgba(0,212,255,0.03),
            0 16px 40px rgba(0,0,0,0.45),
            inset 0 1px 0 rgba(255,255,255,0.04);
        transform: perspective(800px) rotateX(0);
        transition: transform 0.25s, box-shadow 0.25s, border-color 0.25s;
        cursor: default;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 2px;
        background: var(--accent-color, var(--cyan));
        opacity: 0.35;
        border-radius: 2px 2px 0 0;
    }

    .stat-card:hover {
        transform: perspective(800px) rotateX(3deg) translateY(-4px);
        border-color: rgba(0,212,255,0.18);
        box-shadow:
            0 0 30px rgba(0,212,255,0.06),
            0 24px 56px rgba(0,0,0,0.55),
            inset 0 1px 0 rgba(255,255,255,0.06);
    }

    .stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
    }

    .stat-lbl {
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #0e2035;
    }

    .stat-icon-box {
        width: 36px; height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        background: var(--icon-bg, rgba(0,212,255,0.08));
        color: var(--icon-color, var(--cyan));
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.05);
    }

    .stat-num {
        font-size: 2rem;
        font-weight: 900;
        letter-spacing: -1px;
        line-height: 1;
        background: linear-gradient(135deg, #fff 0%, var(--accent-color, var(--cyan)) 120%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-desc {
        font-size: 0.68rem;
        color: #0e2035;
        margin-top: 4px;
        font-weight: 600;
    }

    /* ── TABLE CARD ────────────────────────── */
    .table-card {
        background: var(--glass);
        border: 1px solid var(--glass-b);
        border-radius: 20px;
        overflow: hidden;
        backdrop-filter: blur(12px);
        box-shadow:
            0 0 0 1px rgba(0,212,255,0.03),
            0 20px 60px rgba(0,0,0,0.5),
            inset 0 1px 0 rgba(255,255,255,0.04);
    }

    /* Top glow line */
    .table-card::before {
        content: '';
        display: block;
        height: 2px;
        background: linear-gradient(90deg, transparent 5%, rgba(0,212,255,0.35) 40%, rgba(0,102,255,0.25) 65%, transparent 95%);
    }

    .table-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 24px;
        border-bottom: 1px solid rgba(0,212,255,0.05);
    }

    .table-head h2 {
        font-size: 0.88rem;
        font-weight: 700;
        color: #c0d8f0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .table-head h2 i { color: var(--cyan); }

    .count-chip {
        background: rgba(0,212,255,0.06);
        border: 1px solid rgba(0,212,255,0.1);
        color: #2a5070;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 3px 12px;
        border-radius: 20px;
    }

    table { width: 100%; border-collapse: collapse; }

    thead th {
        background: rgba(0,0,0,0.2);
        color: #0e2035;
        font-size: 0.64rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        padding: 11px 24px;
        text-align: left;
        border-bottom: 1px solid rgba(0,212,255,0.04);
    }

    tbody td {
        padding: 15px 24px;
        border-bottom: 1px solid rgba(0,212,255,0.03);
        font-size: 0.83rem;
        vertical-align: middle;
    }

    tbody tr:last-child td { border-bottom: none; }

    tbody tr {
        transition: background 0.15s;
    }

    tbody tr:hover { background: rgba(0,212,255,0.025); }

    /* Time pill */
    .time-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: rgba(0,212,255,0.06);
        border: 1px solid rgba(0,212,255,0.12);
        color: var(--cyan);
        border-radius: 8px;
        padding: 5px 11px;
        font-weight: 800;
        font-size: 0.85rem;
        font-variant-numeric: tabular-nums;
    }

    /* Patient info */
    .pt-name {
        font-weight: 700;
        color: #c0d8f0;
        font-size: 0.85rem;
        margin-bottom: 3px;
    }

    .pt-contact {
        font-size: 0.72rem;
        color: #0e2035;
    }

    .pt-contact i { margin-right: 3px; }

    /* Doctor */
    .doc-cell {
        font-size: 0.82rem;
        color: #2a4060;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Status badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 0.68rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .s-agendado  { background: rgba(234,179,8,0.12);    border: 1px solid rgba(234,179,8,0.2);    color: #fbbf24; }
    .s-concluido { background: rgba(16,185,129,0.1);    border: 1px solid rgba(16,185,129,0.2);   color: #6ee7b7; }
    .s-cancelado { background: rgba(239,68,68,0.1);     border: 1px solid rgba(239,68,68,0.2);    color: #fca5a5; }

    /* Action buttons */
    .action-link {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 13px;
        border-radius: 8px;
        font-size: 0.73rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.18s;
    }

    .ac-conclude {
        background: rgba(16,185,129,0.1);
        border: 1px solid rgba(16,185,129,0.18);
        color: #6ee7b7;
    }

    .ac-conclude:hover {
        background: rgba(16,185,129,0.18);
        box-shadow: 0 0 16px rgba(16,185,129,0.15);
        color: #6ee7b7;
    }

    .ac-cancel {
        background: rgba(239,68,68,0.08);
        border: 1px solid rgba(239,68,68,0.16);
        color: #fca5a5;
        margin-left: 6px;
    }

    .ac-cancel:hover {
        background: rgba(239,68,68,0.16);
        box-shadow: 0 0 16px rgba(239,68,68,0.12);
        color: #fca5a5;
    }

    .done-label {
        font-size: 0.72rem;
        color: #0e2035;
        font-style: italic;
    }

    /* EMPTY STATE */
    .empty-state {
        text-align: center;
        padding: 72px 24px;
    }

    .empty-icon {
        width: 72px; height: 72px;
        background: rgba(0,212,255,0.04);
        border: 1px solid rgba(0,212,255,0.08);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 18px;
        font-size: 1.8rem;
        color: #0e2035;
    }

    .empty-state h3 {
        font-size: 1rem;
        font-weight: 700;
        color: #1e3050;
        margin-bottom: 6px;
    }

    .empty-state p { font-size: 0.8rem; color: #0e2035; }

    @media (max-width: 900px) {
        .sidebar { display: none; }
        .main    { margin-left: 0; }
        .stats-row { grid-template-columns: repeat(2, 1fr); }
        .page-body { padding: 20px 16px 40px; }
        .topbar { padding: 0 16px; }
    }

    @media (max-width: 480px) {
        .stats-row { grid-template-columns: repeat(2, 1fr); gap: 10px; }
    }
    </style>
</head>
<body>

<?php
$total      = count($agendamentos);
$agendados  = count(array_filter($agendamentos, fn($a) => $a['status'] === 'agendado'));
$concluidos = count(array_filter($agendamentos, fn($a) => $a['status'] === 'concluido'));
$cancelados = count(array_filter($agendamentos, fn($a) => $a['status'] === 'cancelado'));
$dataPtBR   = date('d/m/Y', strtotime($data_desejada));
$diaSemana  = ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'][date('w', strtotime($data_desejada))];
?>

<!-- SIDEBAR -->
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
        <a href="index.php?acao=painel" class="nav-item active">
            <i class="bi bi-calendar2-heart"></i> Agenda do Dia
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

<!-- MAIN -->
<main class="main">

    <!-- TOP BAR -->
    <header class="topbar">
        <div class="topbar-title">
            <h1>Agenda de Consultas</h1>
            <p><?= $diaSemana ?>, <?= $dataPtBR ?></p>
        </div>
        <form method="GET" action="index.php" class="date-form">
            <input type="hidden" name="acao" value="painel">
            <input type="date" name="data" value="<?= htmlspecialchars($data_desejada) ?>">
            <button type="submit" class="btn-filter">
                <i class="bi bi-funnel-fill me-1"></i>Filtrar
            </button>
        </form>
    </header>

    <div class="page-body">

        <!-- STATS — 3D cards -->
        <div class="stats-row">
            <div class="stat-card" style="--accent-color: #00d4ff; --icon-bg: rgba(0,212,255,0.08); --icon-color: #00d4ff;">
                <div class="stat-top">
                    <div class="stat-lbl">Total</div>
                    <div class="stat-icon-box"><i class="bi bi-calendar3"></i></div>
                </div>
                <div class="stat-num"><?= $total ?></div>
                <div class="stat-desc">consultas no dia</div>
            </div>

            <div class="stat-card" style="--accent-color: #fbbf24; --icon-bg: rgba(234,179,8,0.08); --icon-color: #fbbf24;">
                <div class="stat-top">
                    <div class="stat-lbl">Agendadas</div>
                    <div class="stat-icon-box"><i class="bi bi-hourglass-split"></i></div>
                </div>
                <div class="stat-num"><?= $agendados ?></div>
                <div class="stat-desc">aguardando atendimento</div>
            </div>

            <div class="stat-card" style="--accent-color: #6ee7b7; --icon-bg: rgba(16,185,129,0.08); --icon-color: #6ee7b7;">
                <div class="stat-top">
                    <div class="stat-lbl">Concluídas</div>
                    <div class="stat-icon-box"><i class="bi bi-check-circle"></i></div>
                </div>
                <div class="stat-num"><?= $concluidos ?></div>
                <div class="stat-desc">realizadas com sucesso</div>
            </div>

            <div class="stat-card" style="--accent-color: #fca5a5; --icon-bg: rgba(239,68,68,0.08); --icon-color: #fca5a5;">
                <div class="stat-top">
                    <div class="stat-lbl">Canceladas</div>
                    <div class="stat-icon-box"><i class="bi bi-x-circle"></i></div>
                </div>
                <div class="stat-num"><?= $cancelados ?></div>
                <div class="stat-desc">cancelamentos</div>
            </div>
        </div>

        <!-- TABLE -->
        <div class="table-card">
            <div class="table-head">
                <h2><i class="bi bi-list-columns-reverse"></i> Consultas do Dia</h2>
                <span class="count-chip"><?= $total ?> registros</span>
            </div>

            <?php if (empty($agendamentos)): ?>
            <div class="empty-state">
                <div class="empty-icon"><i class="bi bi-calendar-x"></i></div>
                <h3>Nenhuma consulta para este dia</h3>
                <p>Selecione outra data ou aguarde novos agendamentos.</p>
            </div>
            <?php else: ?>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Horário</th>
                            <th>Paciente</th>
                            <th>Médico</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($agendamentos as $a): ?>
                        <tr>
                            <td>
                                <span class="time-pill">
                                    <i class="bi bi-clock-fill" style="font-size:.75rem;"></i>
                                    <?= substr($a['hora_inicio'], 0, 5) ?>
                                </span>
                            </td>
                            <td>
                                <div class="pt-name"><?= htmlspecialchars($a['paciente_nome']) ?></div>
                                <div class="pt-contact">
                                    <i class="bi bi-envelope"></i><?= htmlspecialchars($a['paciente_email'] ?? 'Não informado') ?>
                                    <?php if (!empty($a['paciente_telefone'])): ?>
                                    &nbsp;·&nbsp;<i class="bi bi-phone"></i><?= htmlspecialchars($a['paciente_telefone']) ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="doc-cell">
                                    <i class="bi bi-person-badge"></i>
                                    <?= htmlspecialchars($a['medico_nome']) ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($a['status'] === 'agendado'): ?>
                                    <span class="status-badge s-agendado">
                                        <i class="bi bi-hourglass-split"></i> Agendado
                                    </span>
                                <?php elseif ($a['status'] === 'concluido'): ?>
                                    <span class="status-badge s-concluido">
                                        <i class="bi bi-check-circle-fill"></i> Concluído
                                    </span>
                                <?php else: ?>
                                    <span class="status-badge s-cancelado">
                                        <i class="bi bi-x-circle-fill"></i> Cancelado
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($a['status'] === 'agendado'): ?>
                                <a href="index.php?acao=atualizar_status&status=concluido&id=<?= $a['id'] ?>&data=<?= $data_desejada ?>"
                                   class="action-link ac-conclude">
                                    <i class="bi bi-check2"></i> Concluir
                                </a>
                                <a href="index.php?acao=atualizar_status&status=cancelado&id=<?= $a['id'] ?>&data=<?= $data_desejada ?>"
                                   class="action-link ac-cancel">
                                    <i class="bi bi-x-lg"></i> Cancelar
                                </a>
                                <?php else: ?>
                                <span class="done-label"><i class="bi bi-dash"></i> Finalizado</span>
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
</main>

</body>
</html>
