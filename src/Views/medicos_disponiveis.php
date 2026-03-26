<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedSaaS - Agendamento Premium</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- VUE.JS -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

    <style>
        :root { --primary: #4f46e5; --primary-hover: #4338ca; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-image: linear-gradient(rgba(15, 23, 42, 0.75), rgba(15, 23, 42, 0.85)), url('https://images.unsplash.com/photo-1551076805-e1869033e561?auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center; background-attachment: fixed; min-height: 100vh; }
        .glass-header { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 24px; box-shadow: 0 30px 50px rgba(0,0,0,0.3); color: white; }
        .doctor-card { background: #ffffff; border-radius: 24px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.2); overflow: hidden; margin-top: 20px; }
        .time-slot-btn { background: #f8fafc; border: 2px solid #e2e8f0; color: #334155; border-radius: 12px; padding: 12px 15px; font-weight: 700; font-size: 1.1rem; transition: all 0.3s ease; width: 100%; }
        .time-slot-btn:hover { background: var(--primary); color: white; border-color: var(--primary); transform: scale(1.05) translateY(-2px); box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3); }
        .date-input { background: rgba(255, 255, 255, 0.9); border: none; border-radius: 12px; padding: 12px 20px; font-weight: 600; color: #334155; }
        .date-input:focus { background: #ffffff; box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.3); outline: none; }
        .btn-search { background: var(--primary); border: none; border-radius: 12px; padding: 12px 30px; font-weight: 700; transition: all 0.3s; }
        .btn-search:hover { background: var(--primary-hover); box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2); transform: translateY(-1px); }
    </style>
</head>
<body class="py-5">

<?php if (isset($_GET['sucesso'])): ?>
    <div class="container max-w-4xl" style="max-width: 900px;">
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-lg border-0 rounded-4 mb-4 p-4" style="background: #ecfdf5; color: #065f46;" role="alert">
            <i class="bi bi-check-circle-fill fs-1 me-3 text-success"></i>
            <div>
                <h4 class="alert-heading fw-bold mb-1">Fantástico! Consulta Confirmada.</h4>
                <p class="mb-0">O horário foi reservado com sucesso no banco de dados.</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
<?php endif; ?>
<!-- Alerta de Erro: Double Booking (Concorrência) -->
<?php if (isset($_GET['erro_ocupado'])): ?>
    <div class="container max-w-4xl" style="max-width: 900px;">
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center shadow-lg border-0 rounded-4 mb-4 p-4" style="background: #fef2f2; color: #991b1b;" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-1 me-3 text-danger"></i>
            <div>
                <h4 class="alert-heading fw-bold mb-1">Poxa, você chegou 1 segundo atrasado!</h4>
                <p class="mb-0">Outro paciente acabou de reservar este exato horário. Por favor, escolha outro disponível abaixo.</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
<?php endif; ?>

<div id="app" class="container max-w-4xl" style="max-width: 900px;">
    <div class="glass-header p-5 mb-5 text-center">
        <h1 class="mb-4" style="font-weight: 800; letter-spacing: -1px; font-size: 2.5rem;">
            <i class="bi bi-heart-pulse-fill text-danger"></i> MedSaaS <span style="color: #60a5fa;">Premium</span>
        </h1>
        
        <form @submit.prevent="buscarHorarios" class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-3 mx-auto" style="max-width: 600px;">
            <div class="input-group shadow-lg rounded-4 p-1" style="background: rgba(255,255,255,0.2);">
                <span class="input-group-text bg-transparent border-0 ps-4 text-white"><i class="bi bi-calendar-event-fill fs-5"></i></span>
                <input type="date" v-model="dataSelecionada" class="form-control date-input me-2">
                <button type="submit" class="btn btn-search text-white px-5 rounded-4 shadow-none">
                    <span v-if="carregando">Buscando...</span>
                    <span v-else>Buscar Horários</span>
                </button>
            </div>
        </form>
    </div>

    <div class="row justify-content-center">
        <div class="col-12">
            <div v-if="carregando" class="text-center p-5">
                <div class="spinner-border text-light" style="width: 3rem; height: 3rem;" role="status"></div>
                <h4 class="text-white mt-3 fw-bold">Consultando API...</h4>
            </div>

            <div v-else-if="medicos.length === 0" class="text-center p-5 rounded-4 border-0" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                <i class="bi bi-calendar-x text-white opacity-50" style="font-size: 5rem;"></i>
                <h3 class="fw-bold text-white mt-3">Nenhum horário livre</h3>
            </div>

            <div v-else>
                <!-- OLHA AQUI A MÁGICA DA COMPONENTIZAÇÃO! -->
                <doctor-card 
                    v-for="medicoItem in medicos" 
                    :key="medicoItem.id" 
                    :medico="medicoItem" 
                    :data-consulta="dataSelecionada">
                </doctor-card>
            </div>
        </div>
    </div>
</div>

<!-- INJEÇÃO DE VARIÁVEIS DO PHP PARA O JS E IMPORTAÇÃO DOS ARQUIVOS -->
<script>
    window.DATA_INICIAL = '<?= date('Y-m-d', strtotime('+1 day')) ?>';
</script>
<script src="assets/js/api.js"></script>
<script src="assets/js/app.js"></script>
<!-- INJEÇÃO DE VARIÁVEIS DO PHP PARA O JS E IMPORTAÇÃO DOS ARQUIVOS -->
<script>
    window.DATA_INICIAL = '<?= date('Y-m-d', strtotime('+1 day')) ?>';
</script>
<script src="assets/js/api.js"></script>
<script src="assets/js/app.js"></script>

<!-- AQUI ESTÁ O SCRIPT DO BOOTSTRAP QUE FALTAVA PARA O (X) FUNCIONAR! -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>