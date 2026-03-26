// Arquivo: src/assets/js/app.js

const { createApp } = Vue;

const app = createApp({
    data() {
        return {
            // Pegamos a data inicial injetada pelo PHP no HTML
            dataSelecionada: window.DATA_INICIAL || '',
            medicos:[],
            carregando: false
        }
    },
    mounted() {
        this.buscarHorarios();
    },
    methods: {
        async buscarHorarios() {
            this.carregando = true;
            // Chamamos o nosso Service isolado (Código limpo!)
            this.medicos = await ApiService.getDisponibilidade(this.dataSelecionada);
            this.carregando = false;
        }
    }
});

// ==========================================
// COMPONENTE: CARTÃO DO MÉDICO (<doctor-card>)
// ==========================================
app.component('doctor-card', {
    // Props são as propriedades que o componente recebe de fora
    props: ['medico', 'dataConsulta'],
    template: `
        <div class="card doctor-card p-4 p-md-5 mb-5">
            <div class="d-flex flex-column align-items-center text-center mb-4 pb-4 border-bottom">
                <h2 class="fw-bolder mb-2" style="color: #0f172a; font-size: 2.2rem;">{{ medico.nome }}</h2>
                <div class="badge px-4 py-2 rounded-pill" style="background: #e0e7ff; color: #4f46e5; font-size: 1.1rem; font-weight: 600;">
                    <i class="bi bi-clipboard2-pulse me-1"></i> {{ medico.especialidade }}
                </div>
            </div>
            
            <h5 class="fw-bold mb-4 text-center" style="color: #475569;"><i class="bi bi-clock-history me-2 text-primary"></i> Selecione um horário:</h5>
            
            <div class="row g-3 justify-content-center">
                <div v-for="horario in medico.horarios_disponiveis" :key="horario" class="col-6 col-md-3">
                    <form method="POST" action="index.php?acao=agendar" class="m-0">
                        <input type="hidden" name="medico_id" :value="medico.id">
                        <input type="hidden" name="data_consulta" :value="dataConsulta">
                        <input type="hidden" name="hora_inicio" :value="horario">
                        <button type="submit" class="time-slot-btn">{{ horario }}</button>
                    </form>
                </div>
            </div>
        </div>
    `
});

// Monta o Vue na tela
app.mount('#app');