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
                try {
                    const response = await axios.get(`/vendas/pedidos/${pedido_id}`);
                    if (response.status === 200 || response.status === 201) {
                        this.dadosModal = {
                            dados: {
                                valor_total: response?.data.valor_total,
                                valor_antecipado: response?.data.valor_antecipado,
                                valor_restante: response?.data.valor_restante
                            },
                            nome_cliente: response?.data.cliente.nome,
                            entrega_retirada: response?.data.entrega_retirada,
                            produtos: response?.data.produtos,
                        };
                        const data = new Date(this.dadosModal.entrega_retirada.data);
                        this.dadosModal.entrega_retirada.data = data.toLocaleDateString('pt-BR');
                        this.modal = true;
                    }
                } catch (error) {
                    window.Toast.fire({ icon: 'error', title: 'Erro ao buscar dados' });
                }
            },

            fecharModal() {
                this.modal = false;
            },

            marcarPedidoComoPago(pedido_id) {
                Swal.fire({
                    title: 'Confirmar pagamento?',
                    text: "Confirmar o pagamento total deste pedido?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: 'lightgreen',
                    cancelButtonColor: '#1f2937',
                    confirmButtonText: '<span style="color: black">Confirmar</span>',
                    cancelButtonText: 'Cancelar'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            Swal.showLoading();
                            const response = await axios.post('/vendas/pedidos/pagar/', {
                                pedido_id: pedido_id
                            });

                            window.Toast.fire({
                                icon: 'success',
                                title: 'Pedido marcado como pago!'
                            });

                            setTimeout(() => {
                                window.location.href = '/vendas/pedidos';
                            }, 2500);

                        } catch (error) {
                            window.Toast.fire({
                                icon: 'error',
                                title: 'Erro ao criar pedido.',
                                text: error.response?.data?.message || 'Erro interno no servidor.'
                            });
                        }
                    }
                });
            },

            async excluirPedido(pedido_id) {
                try {
                    const response = await axios.post('/vendas/pedidos/destroy/', {
                        pedido_id: pedido_id
                    });

                    window.Toast.fire({
                        icon: 'success',
                        title: 'Pedido exluído com sucesso!'
                    });

                    window.location.reload();
                } catch (error) {
                    window.Toast.fire({
                       icon: 'error',
                       title: 'Não foi possível excluir o pedido.',
                       text: error.response?.data?.message || 'Erro interno no servidor.'
                    });
                }
            }
        }
    }
</script>
