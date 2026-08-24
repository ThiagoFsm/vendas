<script>
    import { marcarComoFeito } from '../../funcoes/pedidos';
    export default {
        name: "Pedidos",
        props: ['rota_criar', 'rota_store'],
        data() {
            return {
                modal: false,
                dadosModal: []
            }
        },

        methods: {
            criarPedido() {
                window.location.href = `${this.rota_criar}`;
            },

            async detalhesPedido(pedido_id) {
                try {
                    const response = await axios.get(`/vendas/pedidos/${pedido_id}`);
                    if (response.status === 200 || response.status === 201) {
                        this.dadosModal = {
                            dados: {
                                valor_total: response?.data.valor_total,
                                valor_antecipado: response?.data.valor_antecipado,
                                valor_restante: response?.data.valor_restante,
                                pedido_id: response?.data.id
                            },
                            nome_cliente: response?.data.cliente.nome,
                            entrega_retirada: response?.data.entrega_retirada,
                            produtos: response?.data.produtos
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
                            await axios.post('/vendas/pedidos/pagar/', {
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

            // async marcarPedidoComoFeito(pedido_id) {
            //     try {
            //         $.ajax({
            //             method: 'POST',
            //             url: ${APP_URL} + '/' + 'vendas/pedidos/store?' + pedido_id,
            //         });
            //     } catch (e) {
            //         console.log(e)
            //     }
            // },

            async marcarProdutoComoFeito(pedido_id, produto_id) {
                Swal.fire({
                    title: 'Marcar produto como feito?',
                    text: "Confirmar a produção deste produto?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: 'lightgreen',
                    cancelButtonColor: '#1f2937',
                    confirmButtonText: '<span style="color: black">Confirmar</span>',
                    cancelButtonText: 'Cancelar'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        await marcarComoFeito(pedido_id, produto_id, this.fecharModal);
                        window.location.reload();
                        await this.detalhesPedido();
                    }
                });
            },

            editarPedido(pedidoId) {
                window.location.href = `/vendas/pedidos/edit/${pedidoId}`;
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
