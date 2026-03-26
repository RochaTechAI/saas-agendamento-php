// Arquivo: src/assets/js/api.js

const ApiService = {
    // Função assíncrona que consome a nossa API REST
    async getDisponibilidade(dataDesejada) {
        try {
            const resposta = await fetch(`index.php?acao=api_disponibilidade&data=${dataDesejada}`);
            
            if (resposta.status === 200) {
                const json = await resposta.json();
                return json.medicos; // Retorna apenas a lista de médicos
            }
            return[]; // Se der erro 404, retorna uma lista vazia
            
        } catch (erro) {
            console.error("Erro Crítico ao comunicar com o Backend:", erro);
            return[];
        }
    }
};