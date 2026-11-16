<script>
    import { marcarComoFeito } from "../../funcoes/pedidos";

    export default {
        name: "producao",
        props: ["sabores", 'pedidos'],
        data() {
            return {
                modal: false,
                dadosModal: [],
                termoPesquisa: '',
                filtroProducao: '',
            }
        },

        methods: {
            GerarCorSabor(id) {
                const cores = ['#cd5c5c', '#1e90ff', '#bc8f8f', '#db7093', '#8fbc8f', '#9370db'];
                return cores[id % cores.length];
            },

            limparFiltros() {
                this.termoPesquisa = '';
                this.filtroProducao = '';
            },

            async filtrarItens(sabor_id, sabor_descricao, tamanho_id) {
                try {
                    this.limparFiltros();
                    const response = await axios.get(`/vendas/producao/`, {
                        params: {
                            sabor_id: sabor_id,
                            tamanho_id: tamanho_id
                        }
                    });
                    if (response.status === 200 || response.status === 201) {
                        this.dadosModal = response?.data;
                        this.modal = true;
                    }
                } catch (error) {
                    window.Toast.fire({ icon: 'error', title: `Erro ao buscar produtos do sabor ${sabor_descricao}.` });
                }
            },

            async marcarProdutoComoFeito(pedido_id, produto_id) {
                await marcarComoFeito(pedido_id, produto_id);
                if (this.dadosModal && Array.isArray(this.dadosModal)) {
                    const pedido = this.dadosModal.find(p => p.id === pedido_id);
                    if (pedido && pedido.produtos) {
                        const prod = pedido.produtos.find(pr => pr.id === produto_id);
                        if (prod && prod.pivot) {
                            this.$set(prod.pivot, 'produzido', true);
                        }
                    }
                }
            },

            fecharModal() {
                this.modal = false;
                this.limparFiltros();
            },
        },

        computed: {
            hasFiltrosAtivos() {
                return Boolean(this.termoPesquisa || this.filtroProducao);
            },

            dadosModalFiltrados() {
                if (!this.dadosModal || !Array.isArray(this.dadosModal)) return [];

                return this.dadosModal.filter(pedido => {
                    // 1. Filtro por Cliente
                    if (this.termoPesquisa && this.termoPesquisa.trim()) {
                        const normalizar = (texto) => (texto || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                        const termo = normalizar(this.termoPesquisa.trim());
                        const nomeCliente = normalizar(pedido.cliente?.nome);
                        if (!nomeCliente.includes(termo)) {
                            return false;
                        }
                    }

                    // 2. Filtro por Produção (feito ou à fazer)
                    const produzido = Boolean(pedido.produtos?.[0]?.pivot?.produzido);
                    if (this.filtroProducao === 'feito' && !produzido) {
                        return false;
                    }
                    if (this.filtroProducao === 'a_fazer' && produzido) {
                        return false;
                    }

                    return true;
                });
            },

            totalItens() {
                if (!this.dadosModal || !Array.isArray(this.dadosModal)) return 0;
                return this.dadosModal.reduce((acc, pedido) => {
                    const qtd = pedido.produtos?.reduce((s, p) => s + Number(p.pivot?.quantidade || 0), 0) || 0;
                    return acc + qtd;
                }, 0);
            },

            totalItensFeitos() {
                if (!this.dadosModal || !Array.isArray(this.dadosModal)) return 0;
                return this.dadosModal.reduce((acc, pedido) => {
                    const feitos = pedido.produtos?.reduce((s, p) => {
                        return p.pivot?.produzido ? s + Number(p.pivot?.quantidade || 0) : s;
                    }, 0) || 0;
                    return acc + feitos;
                }, 0);
            },

            totalItensAFazer() {
                return this.totalItens - this.totalItensFeitos;
            }
        }
    }
</script>
