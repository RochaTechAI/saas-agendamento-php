const ApiService = {
    async getDisponibilidade(dataDesejada, medicoId = '') {
        try {
            // Manda o ID do médico na URL pro PHP filtrar
            const resposta = await fetch(`index.php?acao=api_disponibilidade&data=${dataDesejada}&medico=${medicoId}`);
            
            if (resposta.status === 200) {
                const json = await resposta.json();
                return json.medicos; 
            }
            return[];
        } catch (erro) {
            console.error("Erro Crítico:", erro);
            return[];
        }
    },

    // Busca todos os médicos da clínica
    async getMedicosLista() {
        try {
            const resposta = await fetch(`index.php?acao=api_medicos`);
            if (resposta.status === 200) {
                return await resposta.json();
            }
            return[];
        } catch (erro) {
            return[];
        }
    }
};