<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso — MedSaaS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --cyan:  #00d4ff;
        --blue:  #0066ff;
        --dark:  #020812;
    }

    body {
        font-family: 'Inter', sans-serif;
        background: var(--dark);
        min-height: 100vh;
        display: flex;
        align-items: stretch;
        overflow: hidden;
    }

    /* ══════════════════════════════════════
       LEFT PANEL — HOSPITAL 3D SCENE
    ══════════════════════════════════════ */
    .scene-panel {
        flex: 1;
        position: relative;
        overflow: hidden;
        display: none;
    }

    @media (min-width: 900px) { .scene-panel { display: block; } }

    /* Dark hospital interior gradient */
    .scene-bg {
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 80% 60% at 50% 0%,   rgba(0,100,220,0.22) 0%, transparent 65%),
            radial-gradient(ellipse 60% 50% at 10% 100%,  rgba(0,200,255,0.1)  0%, transparent 60%),
            linear-gradient(180deg, #020c1e 0%, #030f22 40%, #020812 100%);
    }

    /* Ceiling light strip */
    .corridor-ceiling {
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg,
            transparent 5%,
            rgba(0,212,255,0.9) 20%, rgba(255,255,255,0.7) 30%, rgba(0,212,255,0.9) 40%,
            transparent 50%,
            rgba(0,212,255,0.9) 60%, rgba(255,255,255,0.7) 70%, rgba(0,212,255,0.9) 80%,
            transparent 95%
        );
        box-shadow: 0 0 50px 8px rgba(0,212,255,0.3), 0 0 120px 20px rgba(0,150,255,0.1);
    }

    /* Light beams */
    .corridor-beams {
        position: absolute;
        top: 4px; left: 0; right: 0;
        height: 60%;
        background:
            linear-gradient(180deg, rgba(0,212,255,0.08) 0%, transparent 100%),
            repeating-linear-gradient(90deg,
                transparent 0px,      transparent 180px,
                rgba(0,212,255,0.02) 180px, rgba(0,212,255,0.02) 181px,
                transparent 181px,   transparent 362px
            );
        mask-image: linear-gradient(180deg, black 0%, transparent 100%);
    }

    /* Perspective floor */
    .corridor-floor {
        position: absolute;
        bottom: -10%;
        left: -30%; right: -30%;
        height: 60%;
        background:
            linear-gradient(90deg,  rgba(0,212,255,0.06) 1px, transparent 1px) 0 0 / 70px 70px,
            linear-gradient(         rgba(0,212,255,0.06) 1px, transparent 1px) 0 0 / 70px 70px,
            rgba(1,6,16,0.8);
        transform: perspective(320px) rotateX(56deg);
        transform-origin: 50% 100%;
        mask-image: linear-gradient(0deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 45%, transparent 80%);
    }

    /* Glowing floor center */
    .corridor-glow {
        position: absolute;
        bottom: 0; left: 50%;
        transform: translateX(-50%);
        width: 70%;
        height: 200px;
        background: radial-gradient(ellipse at center bottom,
            rgba(0,212,255,0.08) 0%, transparent 70%);
        filter: blur(20px);
    }

    /* Floating 3D cross */
    .f-cross {
        position: absolute;
        animation: flt linear infinite;
    }

    .f-cross-inner {
        position: relative;
        width: 100%; height: 100%;
        opacity: 0.08;
    }

    .f-cross-inner::before, .f-cross-inner::after {
        content: '';
        position: absolute;
        background: var(--cyan);
        border-radius: 3px;
    }

    .f-cross-inner::before { width: 100%; height: 32%; top: 34%; left: 0; }
    .f-cross-inner::after  { width: 32%; height: 100%; top: 0; left: 34%; }

    @keyframes flt {
        0%,100% { transform: translateY(0)   rotate(0deg); }
        40%      { transform: translateY(-25px) rotate(8deg); }
        70%      { transform: translateY(12px)  rotate(-5deg); }
    }

    /* Ring decorations */
    .f-ring {
        position: absolute;
        border-radius: 50%;
        border: 1px solid rgba(0,212,255,0.06);
        animation: ringPulse ease-in-out infinite;
    }

    @keyframes ringPulse {
        0%, 100% { transform: scale(1) rotate(0deg);   opacity: 0.5; }
        50%       { transform: scale(1.08) rotate(15deg); opacity: 0.2; }
    }

    /* ECG at bottom of left panel */
    .ecg-panel {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 65px;
        overflow: hidden;
        opacity: 0.25;
    }

    .ecg-panel svg {
        height: 100%;
        width: 200%;
        animation: ecgScroll 4s linear infinite;
    }

    @keyframes ecgScroll {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }

    /* Brand text on left */
    .scene-brand {
        position: absolute;
        top: 48px;
        left: 48px;
        z-index: 2;
    }

    .brand-logo-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 60px;
    }

    .brand-icon {
        width: 44px; height: 44px;
        background: linear-gradient(135deg, var(--blue), var(--cyan));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #fff;
        box-shadow: 0 6px 20px rgba(0,102,255,0.4), 0 0 0 1px rgba(0,212,255,0.2);
    }

    .brand-name {
        font-size: 1.15rem;
        font-weight: 800;
        color: #fff;
        letter-spacing: -0.3px;
    }

    .scene-tagline {
        max-width: 340px;
    }

    .scene-tagline h2 {
        font-size: 2.4rem;
        font-weight: 900;
        letter-spacing: -1.5px;
        line-height: 1.1;
        color: #fff;
        margin-bottom: 14px;
    }

    .scene-tagline h2 span {
        background: linear-gradient(135deg, #00d4ff, #0066ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .scene-tagline p {
        font-size: 0.9rem;
        color: #2a4060;
        line-height: 1.65;
    }

    /* Stats row */
    .scene-stats {
        position: absolute;
        bottom: 80px;
        left: 48px;
        display: flex;
        gap: 32px;
        z-index: 2;
    }

    .stat-item {}

    .stat-num {
        font-size: 1.6rem;
        font-weight: 800;
        color: #fff;
        letter-spacing: -1px;
        background: linear-gradient(135deg, #fff 0%, var(--cyan) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-lbl {
        font-size: 0.7rem;
        color: #1e3050;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    /* ══════════════════════════════════════
       RIGHT PANEL — LOGIN FORM
    ══════════════════════════════════════ */
    .form-panel {
        width: 100%;
        max-width: 480px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 48px;
        background: rgba(2,10,22,0.97);
        border-left: 1px solid rgba(0,212,255,0.06);
        position: relative;
        flex-shrink: 0;
    }

    /* Subtle top glow on form panel */
    .form-panel::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, transparent 0%, rgba(0,212,255,0.5) 50%, transparent 100%);
    }

    .form-inner {
        width: 100%;
        max-width: 360px;
    }

    /* Mobile logo (hidden on desktop) */
    .mobile-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 36px;
    }

    @media (min-width: 900px) { .mobile-brand { display: none; } }

    .mobile-brand .brand-icon {
        width: 38px; height: 38px;
        border-radius: 10px;
        font-size: 17px;
    }

    .mobile-brand span {
        font-size: 1rem;
        font-weight: 800;
        color: #fff;
    }

    .form-title {
        font-size: 1.6rem;
        font-weight: 800;
        color: #e8f0ff;
        letter-spacing: -0.5px;
        margin-bottom: 6px;
    }

    .form-subtitle {
        font-size: 0.85rem;
        color: #1e3050;
        margin-bottom: 36px;
    }

    .error-box {
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(239,68,68,0.08);
        border: 1px solid rgba(239,68,68,0.2);
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 24px;
        color: #fca5a5;
        font-size: 0.83rem;
        font-weight: 500;
    }

    .f-group { margin-bottom: 18px; }

    .f-group label {
        display: block;
        font-size: 0.68rem;
        font-weight: 700;
        color: #1e3050;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 7px;
    }

    .f-input-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }

    .f-icon {
        position: absolute;
        left: 14px;
        color: #1e3050;
        font-size: 0.95rem;
        pointer-events: none;
        transition: color 0.2s;
    }

    .f-input-wrap:focus-within .f-icon { color: var(--cyan); }

    input[type="email"],
    input[type="password"],
    input[type="text"] {
        width: 100%;
        padding: 13px 16px 13px 42px;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(0,212,255,0.1);
        border-radius: 12px;
        font-size: 0.9rem;
        font-family: 'Inter', sans-serif;
        color: #c8e0ff;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    }

    input::placeholder { color: #0e2035; }

    input:focus {
        border-color: rgba(0,212,255,0.35);
        background: rgba(0,212,255,0.03);
        box-shadow: 0 0 0 3px rgba(0,212,255,0.08), 0 0 24px rgba(0,212,255,0.05);
    }

    .f-pass input { padding-right: 44px; }

    .btn-eye {
        position: absolute;
        right: 12px;
        background: none;
        border: none;
        color: #1e3050;
        cursor: pointer;
        font-size: 0.9rem;
        padding: 4px;
        display: flex;
        align-items: center;
        transition: color 0.15s;
    }

    .btn-eye:hover { color: var(--cyan); }

    .btn-login {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #0066ff 0%, #00a8d4 100%);
        color: #fff;
        border: none;
        border-radius: 13px;
        font-size: 0.95rem;
        font-weight: 700;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all 0.25s;
        box-shadow: 0 6px 28px rgba(0,102,255,0.45), inset 0 1px 0 rgba(255,255,255,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 8px;
        position: relative;
        overflow: hidden;
    }

    .btn-login::before {
        content: '';
        position: absolute;
        top: 0; left: -100%; width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.12), transparent);
        transition: left 0.4s;
    }

    .btn-login:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 36px rgba(0,102,255,0.6);
    }

    .btn-login:hover::before { left: 100%; }
    .btn-login:active { transform: translateY(0); }

    .back-link {
        display: block;
        text-align: center;
        margin-top: 28px;
        font-size: 0.8rem;
        color: #1e3050;
        text-decoration: none;
        transition: color 0.15s;
    }

    .back-link:hover { color: var(--cyan); }

    @media (max-width: 900px) {
        body { align-items: center; justify-content: center; }
        .form-panel { max-width: 100%; border-left: none; min-height: 100vh; }
    }

    @media (max-width: 480px) {
        .form-panel { padding: 32px 24px; }
    }
    </style>
</head>
<body>

<!-- LEFT — Hospital 3D Scene -->
<div class="scene-panel" aria-hidden="true">
    <div class="scene-bg"></div>
    <div class="corridor-ceiling"></div>
    <div class="corridor-beams"></div>
    <div class="corridor-floor"></div>
    <div class="corridor-glow"></div>

    <!-- Floating crosses -->
    <div class="f-cross" style="width:50px;height:50px; top:20%; left:65%; animation-duration:10s;">
        <div class="f-cross-inner"></div>
    </div>
    <div class="f-cross" style="width:30px;height:30px; top:45%; left:75%; animation-duration:14s; animation-delay:2s;">
        <div class="f-cross-inner"></div>
    </div>
    <div class="f-cross" style="width:22px;height:22px; top:65%; left:55%; animation-duration:9s; animation-delay:1s;">
        <div class="f-cross-inner"></div>
    </div>

    <!-- Rings -->
    <div class="f-ring" style="width:200px;height:200px; top:15%; left:50%; animation-duration:15s;"></div>
    <div class="f-ring" style="width:130px;height:130px; top:50%; left:60%; animation-duration:20s; animation-delay:4s;"></div>

    <!-- ECG -->
    <div class="ecg-panel">
        <svg viewBox="0 0 1200 65" preserveAspectRatio="none" fill="none">
            <path d="M0,32 L100,32 L120,32 L130,4  L140,62 L150,12 L160,48 L170,32 L300,32
                     L320,32 L330,4  L340,62 L350,12 L360,48 L370,32 L500,32
                     L520,32 L530,4  L540,62 L550,12 L560,48 L570,32 L700,32
                     L720,32 L730,4  L740,62 L750,12 L760,48 L770,32 L900,32
                     L920,32 L930,4  L940,62 L950,12 L960,48 L970,32 L1100,32
                     L1120,32 L1130,4 L1140,62 L1150,12 L1160,48 L1170,32 L1200,32"
                stroke="rgba(0,212,255,0.7)" stroke-width="1.5" vector-effect="non-scaling-stroke"/>
        </svg>
    </div>

    <!-- Brand & tagline -->
    <div class="scene-brand">
        <div class="brand-logo-wrap">
            <div class="brand-icon"><i class="bi bi-hospital-fill"></i></div>
            <span class="brand-name">MedSaaS</span>
        </div>
        <div class="scene-tagline">
            <h2>Gestão clínica<br><span>do futuro</span></h2>
            <p>Painel administrativo inteligente para clínicas modernas. Gerencie sua agenda com eficiência e segurança.</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="scene-stats">
        <div class="stat-item">
            <div class="stat-num">24h</div>
            <div class="stat-lbl">Disponível</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">100%</div>
            <div class="stat-lbl">Seguro</div>
        </div>
    </div>
</div>

<!-- RIGHT — Form -->
<div class="form-panel">
    <div class="form-inner">
        <!-- Mobile brand -->
        <div class="mobile-brand">
            <div class="brand-icon"><i class="bi bi-hospital-fill"></i></div>
            <span>MedSaaS</span>
        </div>

        <h1 class="form-title">Bem-vindo de volta</h1>
        <p class="form-subtitle">Acesse o painel de gestão da clínica</p>

        <?php if (isset($_GET['erro'])): ?>
        <div class="error-box">
            <i class="bi bi-shield-exclamation" style="font-size:1rem; flex-shrink:0;"></i>
            <span>E-mail ou senha incorretos. Verifique seus dados e tente novamente.</span>
        </div>
        <?php endif; ?>

        <form method="POST" action="index.php?acao=logar">
            <div class="f-group">
                <label>E-mail</label>
                <div class="f-input-wrap">
                    <i class="bi bi-envelope f-icon"></i>
                    <input type="email" name="email" placeholder="admin@medsaas.com" required autofocus>
                </div>
            </div>

            <div class="f-group f-pass">
                <label>Senha</label>
                <div class="f-input-wrap">
                    <i class="bi bi-lock f-icon"></i>
                    <input type="password" id="senhaInput" name="senha" placeholder="••••••••" required>
                    <button type="button" class="btn-eye" onclick="togglePass()">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right"></i> Entrar no Painel
            </button>
        </form>

        <a href="index.php" class="back-link">
            <i class="bi bi-arrow-left me-1"></i> Voltar para o agendamento
        </a>
    </div>
</div>

<script>
function togglePass() {
    const input = document.getElementById('senhaInput');
    const icon  = document.getElementById('eyeIcon');
    const isPass = input.type === 'password';
    input.type   = isPass ? 'text' : 'password';
    icon.className = isPass ? 'bi bi-eye-slash' : 'bi bi-eye';
}
</script>
</body>
</html>
