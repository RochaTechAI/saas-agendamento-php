<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedSaaS — Agende sua consulta</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --cyan:    #00d4ff;
        --cyan-2:  #00a8cc;
        --blue:    #0066ff;
        --purple:  #7c3aed;
        --dark:    #020812;
        --dark-2:  #040e1c;
        --glass:   rgba(255,255,255,0.035);
        --glass-b: rgba(0,212,255,0.12);
    }

    html { scroll-behavior: smooth; }

    body {
        font-family: 'Inter', sans-serif;
        background: var(--dark);
        color: #fff;
        min-height: 100vh;
        overflow-x: hidden;
    }

    /* ══════════════════════════════════════════════
       HOSPITAL SCENE — CSS BACKGROUND ART
    ══════════════════════════════════════════════ */
    .scene {
        position: fixed;
        inset: 0;
        z-index: 0;
        overflow: hidden;
        pointer-events: none;
    }

    .scene::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 90% 60% at 50% -10%, rgba(0,100,220,0.18) 0%, transparent 65%),
            radial-gradient(ellipse 50% 40% at 85% 90%,  rgba(124,58,237,0.1)  0%, transparent 60%),
            radial-gradient(ellipse 40% 30% at 10% 70%,  rgba(0,180,255,0.07)  0%, transparent 55%),
            linear-gradient(180deg, #020812 0%, #030e1e 50%, #020812 100%);
    }

    .scene-ceiling {
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg,
            transparent 0%,  transparent 8%,
            rgba(0,212,255,0.9) 12%, rgba(255,255,255,0.7) 20%, rgba(0,212,255,0.9) 28%,
            transparent 33%, transparent 40%,
            rgba(0,212,255,0.9) 44%, rgba(255,255,255,0.7) 50%, rgba(0,212,255,0.9) 56%,
            transparent 61%, transparent 68%,
            rgba(0,212,255,0.9) 72%, rgba(255,255,255,0.7) 80%, rgba(0,212,255,0.9) 88%,
            transparent 93%
        );
        box-shadow:
            0 0 40px 6px rgba(0,212,255,0.35),
            0 0 120px 20px rgba(0,150,255,0.12);
    }

    .scene-rays {
        position: absolute;
        top: 4px; left: 0; right: 0;
        height: 55%;
        background:
            linear-gradient(180deg,
                rgba(0,212,255,0.07) 0%,
                rgba(0,150,255,0.04) 40%,
                transparent 100%
            ),
            repeating-linear-gradient(90deg,
                transparent 0px,
                transparent 200px,
                rgba(0,212,255,0.025) 200px,
                rgba(0,212,255,0.025) 201px,
                transparent 201px,
                transparent 401px
            );
        mask-image: linear-gradient(180deg, rgba(0,0,0,0.8) 0%, transparent 100%);
        -webkit-mask-image: linear-gradient(180deg, rgba(0,0,0,0.8) 0%, transparent 100%);
    }

    .scene-floor {
        position: absolute;
        bottom: -5%;
        left: -20%; right: -20%;
        height: 55%;
        background:
            linear-gradient(90deg,  rgba(0,212,255,0.055) 1px, transparent 1px) 0 0 / 80px 80px,
            linear-gradient(         rgba(0,212,255,0.055) 1px, transparent 1px) 0 0 / 80px 80px;
        transform: perspective(350px) rotateX(58deg);
        transform-origin: 50% 100%;
        mask-image: linear-gradient(0deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.4) 40%, transparent 75%);
        -webkit-mask-image: linear-gradient(0deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.4) 40%, transparent 75%);
    }

    .scene-floor-glow {
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 600px;
        height: 250px;
        background: radial-gradient(ellipse at center bottom,
            rgba(0,212,255,0.07) 0%, transparent 70%);
        filter: blur(20px);
    }

    .cross { position: absolute; animation: floatCross linear infinite; }
    .cross-inner { position: relative; width: 100%; height: 100%; opacity: 0.08; filter: blur(0.5px); }
    .cross-inner::before, .cross-inner::after { content: ''; position: absolute; background: var(--cyan); border-radius: 3px; }
    .cross-inner::before { width: 100%; height: 34%; top: 33%; left: 0; }
    .cross-inner::after  { width: 34%; height: 100%; top: 0; left: 33%; }

    @keyframes floatCross {
        0%   { transform: translateY(0)   rotate(0deg)  scale(1);    opacity: 1; }
        25%  { transform: translateY(-30px) rotate(8deg) scale(1.05); opacity: 0.8; }
        75%  { transform: translateY(15px) rotate(-5deg) scale(0.95); opacity: 0.6; }
        100% { transform: translateY(0)   rotate(0deg)  scale(1);    opacity: 1; }
    }

    .hex-ring { position: absolute; border-radius: 50%; border: 1px solid rgba(0,212,255,0.07); animation: pulseRing ease-in-out infinite; }
    @keyframes pulseRing { 0%, 100% { transform: scale(1) rotate(0deg); opacity: 0.5; } 50% { transform: scale(1.1) rotate(20deg); opacity: 0.2; } }

    .scene-scan { position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, transparent 0%, rgba(0,212,255,0.5) 50%, transparent 100%); animation: scanLine 6s linear infinite; opacity: 0; }
    @keyframes scanLine { 0% { top: 0%; opacity: 0; } 5% { opacity: 0.5; } 95% { opacity: 0.3; } 100% { top: 100%; opacity: 0; } }

    .dna { position: absolute; right: 6%; top: 15%; display: flex; flex-direction: column; gap: 16px; opacity: 0.06; animation: floatDna 10s ease-in-out infinite; }
    .dna-row { display: flex; gap: 30px; align-items: center; }
    .dna-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--cyan); }
    .dna-line { width: 30px; height: 1px; background: linear-gradient(90deg, var(--cyan), transparent); }
    @keyframes floatDna { 0%, 100% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-20px) rotate(3deg); } }

    .ecg-bar { position: absolute; bottom: 0; left: 0; right: 0; height: 70px; overflow: hidden; opacity: 0.2; }
    .ecg-bar svg { height: 100%; width: 200%; animation: ecgScroll 4s linear infinite; }
    @keyframes ecgScroll { from { transform: translateX(0); } to { transform: translateX(-50%); } }

    /* ══════════════════════════════════════════════
       LAYOUT
    ══════════════════════════════════════════════ */
    .page { position: relative; z-index: 1; min-height: 100vh; }

    .hero { padding: 72px 24px 110px; text-align: center; max-width: 860px; margin: 0 auto; }
    .hero-badge { display: inline-flex; align-items: center; gap: 8px; background: rgba(0,212,255,0.07); border: 1px solid rgba(0,212,255,0.18); color: var(--cyan); font-size: 0.68rem; font-weight: 700; padding: 6px 18px; border-radius: 30px; margin-bottom: 28px; letter-spacing: 0.12em; text-transform: uppercase; backdrop-filter: blur(8px); box-shadow: 0 0 20px rgba(0,212,255,0.08); }
    .hero h1 { font-size: clamp(2.4rem, 5.5vw, 4.2rem); font-weight: 900; letter-spacing: -2px; line-height: 1.08; margin-bottom: 18px; }
    .text-gradient { background: linear-gradient(135deg, #fff 0%, #00d4ff 40%, #0066ff 70%, #7c3aed 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .hero-sub { font-size: 1.05rem; color: #4a6080; margin-bottom: 44px; font-weight: 400; }

    /* 3D SEARCH BAR (AGORA COM DROPDOWN DE MÉDICO) */
    .search-card {
        display: flex; align-items: center; gap: 0; max-width: 680px; margin: 0 auto;
        background: rgba(255,255,255,0.03); border: 1px solid rgba(0,212,255,0.14); border-radius: 18px; padding: 6px;
        backdrop-filter: blur(24px); box-shadow: 0 0 0 1px rgba(0,212,255,0.04), 0 25px 70px rgba(0,0,0,0.55), inset 0 1px 0 rgba(255,255,255,0.06);
        transform: perspective(1200px) rotateX(0); transition: box-shadow 0.35s;
    }
    .search-card:focus-within { border-color: rgba(0,212,255,0.28); box-shadow: 0 0 0 1px rgba(0,212,255,0.12), 0 0 40px rgba(0,212,255,0.1), 0 28px 80px rgba(0,0,0,0.6), inset 0 1px 0 rgba(255,255,255,0.08); }
    
    .search-icon-wrap { padding: 0 14px 0 10px; color: var(--cyan); font-size: 1.1rem; flex-shrink: 0; }
    .search-field { flex: 1; min-width: 0; text-align: left; padding: 4px 8px;}
    .search-field label { display: block; font-size: 0.6rem; font-weight: 700; color: #2a4060; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 2px; }
    .search-field input[type="date"] { background: none; border: none; color: #fff; font-size: 0.95rem; font-weight: 600; font-family: 'Inter', sans-serif; outline: none; width: 100%; cursor: pointer; }
    .search-field input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(0.6) sepia(1) hue-rotate(180deg); cursor: pointer; opacity: 0.6; }
    
    /* Select do Médico */
    .search-field select { background: none; border: none; color: #fff; font-size: 0.95rem; font-weight: 600; font-family: 'Inter', sans-serif; outline: none; width: 100%; cursor: pointer; appearance: none; }
    .search-field select option { background: var(--dark); color: #fff; }

    /* Separador visual entre os campos */
    .search-divider { width: 1px; height: 35px; background: rgba(0,212,255,0.14); margin: 0 8px; }

    .btn-search { background: linear-gradient(135deg, #0066ff, #00b4ff); color: #fff; border: none; border-radius: 12px; padding: 14px 28px; font-size: 0.875rem; font-weight: 700; font-family: 'Inter', sans-serif; cursor: pointer; transition: all 0.25s; white-space: nowrap; display: flex; align-items: center; gap: 7px; flex-shrink: 0; box-shadow: 0 6px 24px rgba(0,102,255,0.4), inset 0 1px 0 rgba(255,255,255,0.15); position: relative; overflow: hidden; }
    .btn-search::before { content: ''; position: absolute; top: 0; left: -100%; right: 100%; bottom: 0; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent); transition: left 0.4s, right 0.4s; }
    .btn-search:hover::before { left: 0; right: -100%; }
    .btn-search:hover { box-shadow: 0 8px 32px rgba(0,102,255,0.55), inset 0 1px 0 rgba(255,255,255,0.2); transform: translateY(-1px); }
    .btn-search:disabled { opacity: 0.55; cursor: not-allowed; transform: none; }

    /* CONTENT AREA */
    .content { max-width: 860px; margin: -54px auto 64px; padding: 0 24px; position: relative; z-index: 2; }
    
    .alert { display: flex; align-items: center; gap: 14px; padding: 16px 20px; border-radius: 16px; margin-bottom: 16px; backdrop-filter: blur(12px); font-size: 0.875rem; font-weight: 500; }
    .alert i { font-size: 1.3rem; flex-shrink: 0; }
    .alert strong { display: block; margin-bottom: 2px; font-size: 0.95rem; }
    .alert small { opacity: 0.75; font-size: 0.78rem; }
    .alert-ok  { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.25); color: #6ee7b7; }
    .alert-err { background: rgba(239,68,68,0.1);  border: 1px solid rgba(239,68,68,0.25);  color: #fca5a5; }

    .loading-state { text-align: center; padding: 80px 0; }
    .loader-ring { width: 56px; height: 56px; border: 2px solid rgba(0,212,255,0.1); border-top-color: var(--cyan); border-radius: 50%; animation: spin 0.8s linear infinite; margin: 0 auto 20px; box-shadow: 0 0 20px rgba(0,212,255,0.2); }
    @keyframes spin { to { transform: rotate(360deg); } }
    .loading-state p { color: #2a4060; font-size: 0.875rem; }

    .empty-state { text-align: center; padding: 70px 24px; background: var(--glass); border: 1px solid var(--glass-b); border-radius: 24px; backdrop-filter: blur(12px); }
    .empty-icon-wrap { width: 80px; height: 80px; background: rgba(0,212,255,0.06); border: 1px solid rgba(0,212,255,0.12); border-radius: 24px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 2rem; color: #2a4060; }
    .empty-state h3 { font-size: 1.1rem; font-weight: 700; color: #4a6080; margin-bottom: 8px; }
    .empty-state p  { font-size: 0.85rem; color: #2a4060; }

    /* DOCTOR CARD — 3D */
    .doctor-card { background: rgba(4,14,28,0.75); border: 1px solid rgba(0,212,255,0.1); border-radius: 24px; overflow: hidden; margin-bottom: 20px; backdrop-filter: blur(20px); box-shadow: 0 0 0 1px rgba(0,212,255,0.04), 0 24px 64px rgba(0,0,0,0.6), inset 0 1px 0 rgba(255,255,255,0.04); transition: transform 0.3s cubic-bezier(.25,.46,.45,.94), border-color 0.3s, box-shadow 0.3s; transform: perspective(1200px) rotateX(0) rotateY(0) translateZ(0); will-change: transform; }
    .doctor-card:hover { transform: perspective(1200px) rotateX(2deg) rotateY(-4deg) translateZ(16px); border-color: rgba(0,212,255,0.22); box-shadow: 0 0 0 1px rgba(0,212,255,0.12), 0 0 50px rgba(0,212,255,0.07), 0 32px 80px rgba(0,0,0,0.7), -8px 8px 32px rgba(0,102,255,0.1), inset 0 1px 0 rgba(255,255,255,0.06); }
    .card-glow-strip { height: 2px; background: linear-gradient(90deg, transparent 0%, rgba(0,212,255,0.0) 20%, rgba(0,212,255,0.5) 50%, rgba(0,212,255,0.0) 80%, transparent 100%); transition: opacity 0.3s; opacity: 0; }
    .doctor-card:hover .card-glow-strip { opacity: 1; }
    .card-head { display: flex; align-items: center; gap: 20px; padding: 28px 28px 22px; border-bottom: 1px solid rgba(0,212,255,0.06); }
    .doc-avatar { width: 64px; height: 64px; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 800; color: #fff; flex-shrink: 0; letter-spacing: -1px; background: linear-gradient(135deg, #0066ff 0%, #00a8cc 50%, #0044aa 100%); box-shadow: inset -4px -4px 10px rgba(0,0,0,0.35), inset 3px 3px 8px rgba(255,255,255,0.12), 0 8px 28px rgba(0,102,255,0.4), 0 0 0 1px rgba(0,212,255,0.2); position: relative; overflow: hidden; }
    .doc-avatar::before { content: ''; position: absolute; top: -30%; left: -20%; width: 70%; height: 55%; background: rgba(255,255,255,0.12); border-radius: 50%; filter: blur(4px); }
    .doc-name { font-size: 1.15rem; font-weight: 700; color: #e8f0ff; margin-bottom: 8px; letter-spacing: -0.3px; }
    .spec-tag { display: inline-flex; align-items: center; gap: 5px; background: rgba(0,212,255,0.08); border: 1px solid rgba(0,212,255,0.16); color: var(--cyan); border-radius: 20px; padding: 3px 12px; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.03em; }
    .card-slots { padding: 22px 28px 28px; }
    .slots-title { font-size: 0.68rem; font-weight: 700; color: #2a4060; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 16px; display: flex; align-items: center; gap: 6px; }
    .slots-title::after { content: ''; flex: 1; height: 1px; background: rgba(0,212,255,0.08); }
    .slots-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(86px, 1fr)); gap: 8px; }
    .slot-btn { background: rgba(0,212,255,0.04); border: 1px solid rgba(0,212,255,0.1); color: #7a9abb; border-radius: 12px; padding: 11px 8px; font-size: 0.88rem; font-weight: 700; font-family: 'Inter', sans-serif; cursor: pointer; transition: all 0.2s cubic-bezier(.25,.46,.45,.94); text-align: center; position: relative; overflow: hidden; }
    .slot-btn::before { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(0,212,255,0.12) 0%, rgba(0,102,255,0.08) 100%); opacity: 0; transition: opacity 0.2s; }
    .slot-btn:hover { border-color: rgba(0,212,255,0.5); color: #fff; transform: translateY(-3px) scale(1.03); box-shadow: 0 0 16px rgba(0,212,255,0.2), 0 8px 24px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.08); background: rgba(0,212,255,0.08); }
    .slot-btn:hover::before { opacity: 1; }
    .slot-btn:active { transform: translateY(-1px) scale(1.01); }

    /* MODAL */
    .modal-overlay { position: fixed; inset: 0; background: rgba(2,8,18,0.85); z-index: 1000; display: flex; align-items: center; justify-content: center; padding: 24px; backdrop-filter: blur(12px); animation: fadeIn 0.2s; }
    @keyframes fadeIn { from { opacity: 0; } }
    .modal-box { background: linear-gradient(145deg, rgba(4,14,28,0.98) 0%, rgba(2,8,20,0.98) 100%); border: 1px solid rgba(0,212,255,0.14); border-radius: 28px; width: 100%; max-width: 460px; box-shadow: 0 0 0 1px rgba(0,212,255,0.06), 0 0 60px rgba(0,212,255,0.06), 0 40px 100px rgba(0,0,0,0.8); overflow: hidden; animation: slideUp 0.28s cubic-bezier(.25,.46,.45,.94); }
    @keyframes slideUp { from { transform: translateY(24px); opacity: 0; } }
    .modal-box::before { content: ''; display: block; height: 2px; background: linear-gradient(90deg, transparent 5%, var(--cyan) 40%, #0066ff 60%, transparent 95%); opacity: 0.6; }
    .modal-header { padding: 22px 24px 18px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(0,212,255,0.07); }
    .modal-header h2 { font-size: 1rem; font-weight: 700; color: #c8e0ff; display: flex; align-items: center; gap: 8px; }
    .modal-header h2 i { color: var(--cyan); }
    .btn-close { width: 30px; height: 30px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.07); border-radius: 8px; cursor: pointer; color: #4a6080; font-size: 0.8rem; display: flex; align-items: center; justify-content: center; transition: all 0.15s; }
    .btn-close:hover { background: rgba(239,68,68,0.15); border-color: rgba(239,68,68,0.2); color: #f87171; }
    .modal-summary { margin: 18px 24px; background: rgba(0,212,255,0.04); border: 1px solid rgba(0,212,255,0.1); border-radius: 14px; padding: 16px; font-size: 0.83rem; color: #4a8aaa; line-height: 2; }
    .modal-summary strong { color: #a0d0ff; }
    .modal-body { padding: 0 24px 24px; }
    .m-field { margin-bottom: 14px; }
    .m-field label { display: block; font-size: 0.68rem; font-weight: 700; color: #2a4060; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 6px; }
    .m-field input { width: 100%; padding: 12px 16px; background: rgba(255,255,255,0.03); border: 1px solid rgba(0,212,255,0.1); border-radius: 12px; font-size: 0.9rem; font-family: 'Inter', sans-serif; color: #c8e0ff; outline: none; transition: border-color 0.2s, box-shadow 0.2s; }
    .m-field input::placeholder { color: #1e3050; }
    .m-field input:focus { border-color: rgba(0,212,255,0.35); box-shadow: 0 0 0 3px rgba(0,212,255,0.08), 0 0 20px rgba(0,212,255,0.05); }
    .btn-confirm { width: 100%; padding: 14px; background: linear-gradient(135deg, #0066ff 0%, #00a0dd 100%); color: #fff; border: none; border-radius: 14px; font-size: 0.95rem; font-weight: 700; font-family: 'Inter', sans-serif; cursor: pointer; transition: all 0.25s; box-shadow: 0 6px 28px rgba(0,102,255,0.45), inset 0 1px 0 rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 6px; position: relative; overflow: hidden; }
    .btn-confirm::before { content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.12), transparent); transition: left 0.4s; }
    .btn-confirm:hover { transform: translateY(-1px); box-shadow: 0 8px 36px rgba(0,102,255,0.6); }
    .btn-confirm:hover::before { left: 100%; }

    .site-footer { text-align: center; padding: 20px; font-size: 0.72rem; color: #1e3050; position: relative; z-index: 1; }
    .site-footer a { color: #2a5080; text-decoration: none; }
    .site-footer a:hover { color: var(--cyan); }

    @media (max-width: 520px) {
        .hero { padding: 50px 16px 90px; }
        .search-card { flex-wrap: wrap; }
        .btn-search { width: 100%; justify-content: center; }
        .slots-grid { grid-template-columns: repeat(3, 1fr); }
        .card-head { padding: 20px; gap: 14px; }
        .doc-avatar { width: 52px; height: 52px; }
    }
    </style>
</head>
<body>

<div class="scene" aria-hidden="true">
    <div class="scene-ceiling"></div>
    <div class="scene-rays"></div>
    <div class="scene-floor"></div>
    <div class="scene-floor-glow"></div>
    <div class="scene-scan"></div>
    <div class="cross" style="width:40px;height:40px; top:12%; left:7%;  animation-duration:9s;  animation-delay:0s;"><div class="cross-inner"></div></div>
    <div class="cross" style="width:28px;height:28px; top:55%; left:3%;  animation-duration:12s; animation-delay:2s;"><div class="cross-inner"></div></div>
    <div class="cross" style="width:55px;height:55px; top:20%; right:4%; animation-duration:11s; animation-delay:1s;"><div class="cross-inner"></div></div>
    <div class="cross" style="width:32px;height:32px; top:70%; right:6%; animation-duration:8s;  animation-delay:3s;"><div class="cross-inner"></div></div>
    <div class="cross" style="width:22px;height:22px; top:40%; left:92%;  animation-duration:14s; animation-delay:0.5s;"><div class="cross-inner"></div></div>
    <div class="hex-ring" style="width:180px;height:180px; top:8%;  left:2%;  animation-duration:14s;"></div>
    <div class="hex-ring" style="width:120px;height:120px; top:60%; right:3%; animation-duration:18s; animation-delay:3s;"></div>
    <div class="hex-ring" style="width:240px;height:240px; bottom:5%; left:40%; animation-duration:22s;"></div>
    <div class="dna">
        <?php for($i=0;$i<8;$i++): ?>
        <div class="dna-row" style="transform: <?= $i%2===0 ? 'translateX(0)' : 'translateX(15px)'; ?>">
            <div class="dna-dot"></div><div class="dna-line"></div><div class="dna-dot"></div>
        </div>
        <?php endfor; ?>
    </div>
    <div class="ecg-bar">
        <svg viewBox="0 0 1200 70" preserveAspectRatio="none" fill="none">
            <path d="M0,35 L100,35 L120,35 L130,5 L140,65 L150,15 L160,50 L170,35 L300,35 L320,35 L330,5 L340,65 L350,15 L360,50 L370,35 L500,35 L520,35 L530,5 L540,65 L550,15 L560,50 L570,35 L700,35 L720,35 L730,5 L740,65 L750,15 L760,50 L770,35 L900,35 L920,35 L930,5 L940,65 L950,15 L960,50 L970,35 L1100,35 L1120,35 L1130,5 L1140,65 L1150,15 L1160,50 L1170,35 L1200,35" stroke="rgba(0,212,255,0.6)" stroke-width="1.5" vector-effect="non-scaling-stroke"/>
        </svg>
    </div>
</div>

<div id="app" class="page">
    <header class="hero">
        <div class="hero-badge"><i class="bi bi-heart-pulse-fill"></i> Agendamento Online &mdash; 24h</div>
        <h1>Cuide da sua saúde<br><span class="text-gradient">com inteligência</span></h1>
        <p class="hero-sub">Encontre médicos disponíveis, escolha o horário ideal e confirme em segundos.</p>

        <!-- BUSCA ATUALIZADA COM O FILTRO DE MÉDICO -->
        <form @submit.prevent="buscarHorarios" class="search-card">
            <div class="search-icon-wrap"><i class="bi bi-calendar-heart-fill"></i></div>
            <div class="search-field">
                <label for="dataBusca">Selecione a data</label>
                <input type="date" id="dataBusca" v-model="dataSelecionada" required>
            </div>
            
            <div class="search-divider"></div>
            
            <div class="search-field">
                <label for="medicoFiltro">Especialista (Opcional)</label>
                <select id="medicoFiltro" v-model="medicoFiltro">
                    <option value="">Todos os médicos</option>
                    <option v-for="m in medicosLista" :key="m.id" :value="m.id">{{ m.nome }} - {{ m.especialidade }}</option>
                </select>
            </div>

            <button type="submit" class="btn-search" :disabled="carregando">
                <i class="bi bi-search"></i> <span>{{ carregando ? 'Buscando...' : 'Buscar Horários' }}</span>
            </button>
        </form>
    </header>

    <main class="content">
        <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-ok">
            <i class="bi bi-check-circle-fill"></i>
            <div><strong>Consulta confirmada!</strong><small>O horário foi reservado com sucesso. Você receberá uma confirmação por e-mail.</small></div>
        </div>
        <?php endif; ?>

        <?php if (isset($_GET['erro_ocupado'])): ?>
        <div class="alert alert-err">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div><strong>Horário indisponível</strong><small>Outro paciente acabou de reservar este horário. Por favor, selecione outro.</small></div>
        </div>
        <?php endif; ?>

        <div v-if="carregando" class="loading-state">
            <div class="loader-ring"></div>
            <p>Verificando disponibilidade dos médicos...</p>
        </div>

        <div v-else-if="buscou && medicos.length === 0" class="empty-state">
            <div class="empty-icon-wrap"><i class="bi bi-calendar-x"></i></div>
            <h3>Nenhum horário disponível</h3>
            <p>Não encontramos médicos disponíveis nesta data e filtro.<br>Experimente alterar a busca.</p>
        </div>

        <div v-else>
            <div v-for="medico in medicos" :key="medico.id" class="doctor-card" @mousemove="tilt3d($event)" @mouseleave="resetTilt($event)">
                <div class="card-glow-strip"></div>
                <div class="card-head">
                    <div class="doc-avatar">{{ iniciais(medico.nome) }}</div>
                    <div>
                        <div class="doc-name">{{ medico.nome }}</div>
                        <span class="spec-tag"><i class="bi bi-clipboard2-pulse"></i> {{ medico.especialidade }}</span>
                    </div>
                </div>
                <div class="card-slots">
                    <p class="slots-title"><i class="bi bi-clock-history"></i> {{ medico.horarios_disponiveis.length }} horário(s) disponível(is)</p>
                    <div class="slots-grid">
                        <button v-for="horario in medico.horarios_disponiveis" :key="horario" class="slot-btn" @click="abrirModal(medico, horario)">{{ horario }}</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="site-footer">&copy; 2026 MedSaaS &nbsp;&middot;&nbsp; <a href="index.php?acao=login">Acesso administrativo</a></footer>

    <div v-if="modalAberto && medicoSelecionado" class="modal-overlay" @click.self="fecharModal">
        <div class="modal-box">
            <div class="modal-header">
                <h2><i class="bi bi-calendar-plus"></i> Confirmar Agendamento</h2>
                <button class="btn-close" @click="fecharModal"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-summary">
                <div><i class="bi bi-person-badge me-2"></i><strong>{{ medicoSelecionado.nome }}</strong></div>
                <div><i class="bi bi-calendar3 me-2"></i>{{ formatarData(dataSelecionada) }} &nbsp;·&nbsp; <strong>{{ horarioSelecionado }}</strong></div>
                <div><i class="bi bi-clipboard2-pulse me-2"></i>{{ medicoSelecionado.especialidade }}</div>
            </div>
            <div class="modal-body">
                <form method="POST" action="index.php?acao=agendar">
                    <input type="hidden" name="medico_id" :value="medicoSelecionado.id">
                    <input type="hidden" name="data_consulta" :value="dataSelecionada">
                    <input type="hidden" name="hora_inicio" :value="horarioSelecionado">
                    <div class="m-field"><label>Nome completo</label><input type="text" name="paciente_nome" placeholder="Seu nome completo" required></div>
                    <div class="m-field"><label>E-mail</label><input type="email" name="paciente_email" placeholder="seu@email.com" required></div>
                    <div class="m-field"><label>WhatsApp / Telefone</label><input type="text" name="paciente_telefone" placeholder="(11) 99999-9999" required></div>
                    <button type="submit" class="btn-confirm"><i class="bi bi-check-circle"></i> Confirmar Consulta</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>window.DATA_INICIAL = '<?= date('Y-m-d', strtotime('+1 day')) ?>';</script>
<script src="assets/js/api.js?v=3"></script>
<script>
const { createApp } = Vue;

createApp({
    data() {
        return {
            dataSelecionada: window.DATA_INICIAL || '',
            medicoFiltro: '', // O ID do médico escolhido no dropdown
            medicosLista: [], // A lista de médicos que vem do banco
            medicos:[],
            carregando: false,
            buscou: false,
            medicoSelecionado: null,
            horarioSelecionado: '',
            modalAberto: false
        };
    },
    async mounted() {
        // Carrega a lista do dropdown assim que a tela abre
        try { this.medicosLista = await ApiService.getMedicosLista(); } catch(e) {}
        
        if (this.dataSelecionada) this.buscarHorarios();
    },
    methods: {
        async buscarHorarios() {
            if (!this.dataSelecionada) return;
            this.carregando = true;
            this.buscou = false;
            // Manda o ID do filtro para a API!
            this.medicos = await ApiService.getDisponibilidade(this.dataSelecionada, this.medicoFiltro);
            this.carregando = false;
            this.buscou = true;
        },
        abrirModal(medico, horario) {
            this.medicoSelecionado = medico;
            this.horarioSelecionado = horario;
            this.modalAberto = true;
            document.body.style.overflow = 'hidden';
        },
        fecharModal() {
            this.modalAberto = false;
            document.body.style.overflow = '';
        },
        iniciais(nome) { return nome.split(' ').filter(Boolean).slice(0,2).map(n => n[0]).join('').toUpperCase(); },
        formatarData(iso) { if (!iso) return ''; const [y,m,d] = iso.split('-'); return `${d}/${m}/${y}`; },
        tilt3d(e) {
            const el = e.currentTarget; const rect = el.getBoundingClientRect();
            const x = e.clientX - rect.left; const y = e.clientY - rect.top;
            const cx = rect.width / 2; const cy = rect.height / 2;
            const rotY = ((x - cx) / cx) * -5; const rotX = ((y - cy) / cy) * 3;
            el.style.transform = `perspective(1200px) rotateX(${rotX}deg) rotateY(${rotY}deg) translateZ(12px)`;
        },
        resetTilt(e) { e.currentTarget.style.transform = 'perspective(1200px) rotateX(0) rotateY(0) translateZ(0)'; }
    }
}).mount('#app');
</script>
</body>
</html>