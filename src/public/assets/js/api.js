const ApiService = {
    // Busca os horários livres, agora aceitando o ID do médico como filtro!
    async getDisponibilidade(dataDesejada, medicoId = '') {
        try {
            const resposta = await fetch(`index.php?acao=api_disponibilidade&data=${dataDesejada}&medico=${medicoId}`);
            
            if (resposta.status === 200) {
                const json = await resposta.json();
                return json.medicos; 
            }
            return[];
            
        } catch (erro) {
            console.error("Erro Crítico ao comunicar com o Backend:", erro);
            return[];
        }
    },

    // Busca a lista de todos os médicos para popular a caixinha de Seleção
    async getMedicosLista() {
        try {
            const resposta = await fetch(`index.php?acao=api_medicos`);
            if (resposta.status === 200) {
                return await resposta.json();
            }
            return [];
        } catch (erro) {
            return[];
        }
    }
};