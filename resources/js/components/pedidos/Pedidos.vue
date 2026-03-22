<script>
    export default {
        name: "Pedidos",
        props: ['rota_create'],
        data() {
            return {
                modal: false,
                dadosModal: []
            }
        },

        methods: {
            criarPedido() {
                window.location.href = `${this.rota_create}`;
            },

            async detalhesPedido(pedido_id) {
                this.modal = true;

                try {
                    const response = await axios.get(`/vendas/pedidos/${pedido_id}`);
                    if (response.status === 200 || response.status === 201) {
                        console.log(response.data);
                        this.dadosModal = {
                            dados: {
                                valor_total: response.data.valor_total,
                                valor_antecipado: response.data.valor_antecipado,
                                valor_restante: response.data.valor_restante
                            },
                            nome_cliente: response.data.cliente.nome,
                            entrega_retirada: response.data.entrega_retirada,
                            produtos: response.data.produtos,
                        };
                        const data = new Date(this.dadosModal.entrega_retirada.data);
                        this.dadosModal.entrega_retirada.data = data.toLocaleDateString('pt-BR');
                    }
                } catch (error) {
                    window.Toast.fire({ icon: 'error', title: 'Erro ao buscar dados' });
                }
            },

            fecharModal() {
                this.modal = false;
            },
        }
    }
</script>

<style scoped>
.modal-overlay {
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex; align-items: center; justify-content: center;
}
.modal-content {
    background: white;
    padding: 20px;
    border-radius: 8px;
    min-width: 300px;
}
</style>
