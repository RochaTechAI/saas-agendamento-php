const { createApp } = Vue;

const app = createApp({
    data() {
        return {
            dataSelecionada: window.DATA_INICIAL || '',
            medicos: new Array(),
            carregando: false,
            
            // Dados para o Modal Pop-up
            medicoSelecionado: null,
            horarioSelecionado: ''
        }
    },
    mounted() {
        this.buscarHorarios();
    },
    methods: {
        async buscarHorarios() {
            this.carregando = true;
            this.medicos = await ApiService.getDisponibilidade(this.dataSelecionada);
            this.carregando = false;
        },
        
        prepararAgendamento(medico, horario) {
            this.medicoSelecionado = medico;
            this.horarioSelecionado = horario;
            const modal = new bootstrap.Modal(document.getElementById('modalAgendamento'));
            modal.show();
        }
    }
});

app.component('doctor-card', {
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
                    <button type="button" class="time-slot-btn" @click="$emit('abrir-modal', medico, horario)">
                        {{ horario }}
                    </button>
                </div>
            </div>
        </div>
    `
});

app.mount('#app');