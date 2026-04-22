const { createApp } = Vue;

const app = createApp({
    data() {
        return {
            clinicaId: window.CLINICA_ID || 1, // Pega da injeção do PHP
            clinicaSlug: window.CLINICA_SLUG || 'vida-saudavel',
            dataSelecionada: window.DATA_INICIAL || '',
            medicoFiltro: '',
            medicosLista: [],
            medicos:[],
            carregando: false, buscou: false, medicoSelecionado: null, horarioSelecionado: '', modalAberto: false
        };
    },
    async mounted() {
        try { this.medicosLista = await ApiService.getMedicosLista(this.clinicaId); } catch(e) {}
        if (this.dataSelecionada) this.buscarHorarios();
    },
    methods: {
        async buscarHorarios() {
            if (!this.dataSelecionada) return;
            this.carregando = true; this.buscou = false;
            this.medicos = await ApiService.getDisponibilidade(this.clinicaId, this.dataSelecionada, this.medicoFiltro);
            this.carregando = false; this.buscou = true;
        },
        abrirModal(medico, horario) {
            this.medicoSelecionado = medico; this.horarioSelecionado = horario; this.modalAberto = true; document.body.style.overflow = 'hidden';
        },
        fecharModal() { this.modalAberto = false; document.body.style.overflow = ''; },
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
});

app.component('doctor-card', {
    props:['medico', 'dataConsulta'],
    template: `
        <div class="card doctor-card p-4 p-md-5 mb-4" style="background: rgba(3, 15, 34, 0.95); border: 1px solid rgba(0, 212, 255, 0.1); border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
            <div class="d-flex flex-column flex-md-row align-items-center mb-4">
                <div class="me-md-4 mb-3 mb-md-0" style="width: 80px; height: 80px; border-radius: 20px; background: linear-gradient(135deg, #00d4ff, #0066ff); display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 800; color: white; box-shadow: 0 8px 20px rgba(0,102,255,0.4);">
                    {{ medico.nome.substring(0, 2).toUpperCase() }}
                </div>
                <div class="text-center text-md-start">
                    <h3 class="fw-bolder mb-2 text-white" style="letter-spacing: -0.5px;">{{ medico.nome }}</h3>
                    <div class="badge px-3 py-2 rounded-pill" style="background: rgba(0, 212, 255, 0.08); color: #00d4ff; border: 1px solid rgba(0, 212, 255, 0.15); font-weight: 600;">
                        <i class="bi bi-clipboard2-pulse me-1"></i> {{ medico.especialidade }}
                    </div>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                <span style="color: #4a6a8a; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; white-space: nowrap;">
                    <i class="bi bi-clock"></i> {{ medico.horarios_disponiveis.length }} HORÁRIO(S)
                </span>
                <div style="height: 1px; flex: 1; background: rgba(255,255,255,0.05);"></div>
            </div>
            <div class="row g-3 justify-content-start">
                <div v-for="horario in medico.horarios_disponiveis" :key="horario" class="col-4 col-md-3 col-lg-2">
                    <button type="button" class="btn w-100" style="background: transparent; color: #a3c2df; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px; padding: 12px 10px; font-weight: 600; font-size: 0.9rem; transition: all 0.2s;" onmouseover="this.style.background='rgba(0, 212, 255, 0.05)'; this.style.borderColor='rgba(0,212,255,0.3)'; this.style.color='#fff'" onmouseout="this.style.background='transparent'; this.style.borderColor='rgba(255, 255, 255, 0.08)'; this.style.color='#a3c2df'" @click="$emit('abrir-modal', medico, horario)">
                        {{ horario }}
                    </button>
                </div>
            </div>
        </div>
    `
});
app.mount('#app');