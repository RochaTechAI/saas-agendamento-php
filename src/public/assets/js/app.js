const { createApp } = Vue;

const app = createApp({
    data() {
        return {
            dataSelecionada: window.DATA_INICIAL || '',
            medicos: [],
            carregando: false,
            medicoSelecionado: null,
            horarioSelecionado: ''
        };
    },
    mounted() {
        if (this.dataSelecionada) {
            this.buscarHorarios();
        }
    },
    methods: {
        async buscarHorarios() {
            if (!this.dataSelecionada) return;
            this.carregando = true;
            this.medicos = await ApiService.getDisponibilidade(this.dataSelecionada);
            this.carregando = false;
        },
        prepararAgendamento(medico, horario) {
            this.medicoSelecionado = medico;
            this.horarioSelecionado = horario;
            const modal = new bootstrap.Modal(document.getElementById('modalAgendamento'));
            modal.show();
        },
        formatarData(dataISO) {
            if (!dataISO) return '';
            const [ano, mes, dia] = dataISO.split('-');
            return `${dia}/${mes}/${ano}`;
        }
    }
});

app.component('doctor-card', {
    props: ['medico', 'dataConsulta'],
    emits: ['abrir-modal'],
    template: `
        <div class="doctor-card">
            <div class="doctor-card-header">
                <div class="doctor-avatar">{{ medico.nome.split(' ').slice(0,2).map(n => n[0]).join('') }}</div>
                <div>
                    <h3 class="doctor-name">{{ medico.nome }}</h3>
                    <span class="specialty-badge">
                        <i class="bi bi-clipboard2-pulse me-1"></i>{{ medico.especialidade }}
                    </span>
                </div>
            </div>
            <div class="slots-section">
                <p class="slots-label">
                    <i class="bi bi-clock me-1"></i>
                    {{ medico.horarios_disponiveis.length }} horário(s) disponível(is)
                </p>
                <div class="slots-grid">
                    <button
                        v-for="horario in medico.horarios_disponiveis"
                        :key="horario"
                        class="slot-btn"
                        @click="$emit('abrir-modal', medico, horario)"
                    >
                        {{ horario }}
                    </button>
                </div>
            </div>
        </div>
    `
});

app.mount('#app');
