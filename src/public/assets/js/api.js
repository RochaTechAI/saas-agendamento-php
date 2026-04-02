const ApiService = {
    async getDisponibilidade(dataDesejada) {
        try {
            const resposta = await fetch(`index.php?acao=api_disponibilidade&data=${dataDesejada}`);

            if (resposta.status === 200) {
                const json = await resposta.json();
                return json.medicos;
            }

            return [];
        } catch (erro) {
            console.error('Erro ao comunicar com o backend:', erro);
            return [];
        }
    }
};
