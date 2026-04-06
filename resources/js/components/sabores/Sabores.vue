<script>
    export default {
        name: "Sabores",
        props: ['sabores', 'pedidos'],
        data() {
            return {
                modal: false,
                dadosModal: [],
            }
        },

        mounted() {
            // console.log(this.pedidos)
        },

        methods: {
            GerarCorSabor(id) {
                const cores = ['#cd5c5c', '#1e90ff', '#bc8f8f', '#db7093', '#8fbc8f', '#9370db'];
                // Isso garante que o mesmo ID sempre receba a mesma cor da lista
                return cores[id % cores.length];
            },

            async filtrarItens(sabor_id, sabor_descricao, tamanho_id) {
                try {
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

            async marcarComoFeito(pedido_id, produto_id) {
                try {
                    const response = await axios.post('/vendas/producao/marcar-produto-feito', {
                        pedido_id: pedido_id,
                        produto_id: produto_id
                    });
                    if (response.status === 200 || response.status === 201) {
                        window.Toast.fire({ icon: 'success', title: `Pedido ${pedido_id} atualizado!` });
                    }
                } catch (error) {
                    window.Toast.fire({ icon:'error', title: `Não foi possível atualizar o pedido ${pedido_id}.` });
                }
            },

            fecharModal() {
                this.modal = false;
            },
        }
    }
</script>
