<script>
    import { marcarComoFeito } from '../../funcoes/pedidos';
    export default {
        name: "Pedidos",
        props: ['rota_pedidos', 'rota_criar', 'rota_store'],
        data() {
            return {
                pedidos: [],
                carregando: false,
                modal: false,
                dadosModal: []
            }
        },

        async mounted() {
            await this.buscarPedidos();
        },

        methods: {
            async buscarPedidos() {
                this.carregando = true;
                try {
                    const endpoint = this.rota_pedidos || '/vendas/pedidos';
                    const response = await axios.get(endpoint);
                    this.pedidos = response.data || [];
                } catch (error) {
                    window.Toast.fire({
                        icon: 'error',
                        title: 'Erro ao carregar lista de pedidos.'
                    });
                } finally {
                    this.carregando = false;
                }
            },

            criarPedido() {
                window.location.href = `${this.rota_criar}`;
            },

            async detalhesPedido(pedido_id) {
                try {
                    const endpoint = this.rota_pedidos ? `${this.rota_pedidos}/${pedido_id}` : `/vendas/pedidos/${pedido_id}`;
                    const response = await axios.get(endpoint);

                    this.dadosModal = {
                        dados: {
                            valor_total: response?.data.valor_total,
                            valor_antecipado: response?.data.valor_antecipado,
                            valor_restante: response?.data.valor_restante,
                            pedido_id: response?.data.id
                        },
                        pago: response?.data.pago,
                        produzido: response.data.produzido,
                        nome_cliente: response?.data.cliente.nome,
                        entrega_retirada: response?.data.entrega_retirada,
                        produtos: response?.data.produtos
                    };
                    const data = new Date(this.dadosModal.entrega_retirada.data);
                    this.dadosModal.entrega_retirada.data = data.toLocaleDateString('pt-BR');
                    this.modal = true;

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

                            const endpoint = this.rota_pedidos ? `${this.rota_pedidos}/${pedido_id}/pagar` : `/vendas/pedidos/${pedido_id}/pagar`;
                            const response = await $.ajax({
                                type: 'POST',
                                url: endpoint,
                            });

                            window.Toast.fire({
                                icon: 'success',
                                title: response?.message
                            });

                            this.fecharModal();
                            await this.buscarPedidos();

                        } catch (error) {
                            window.Toast.fire({
                                icon: 'error',
                                title: 'Erro ao atualizar pedido.',
                                text: error?.message || 'Erro interno no servidor.'
                            });
                        }
                    }
                });
            },

            async marcarPedidoComoFeito(pedido_id) {
                Swal.fire({
                    title: 'Marcar pedido como feito?',
                    text: "Confirmar a produção de todos os itens deste pedido?",
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

                            const endpoint = this.rota_pedidos ? `${this.rota_pedidos}/${pedido_id}/produzir` : `/vendas/pedidos/${pedido_id}/produzir`;
                            const response = await axios.post(endpoint);

                            window.Toast.fire({
                                icon: 'success',
                                title: response?.data?.message || 'Pedido marcado como feito com sucesso!'
                            });

                            this.fecharModal();
                            await this.buscarPedidos();

                        } catch (error) {
                            window.Toast.fire({
                                icon: 'error',
                                title: 'Erro ao atualizar pedido.',
                                text: error?.response?.data?.message || 'Erro interno no servidor.'
                            });
                        }
                    }
                });
            },

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
                        await marcarComoFeito(pedido_id, produto_id);
                        await this.detalhesPedido(pedido_id);
                        await this.buscarPedidos();
                    }
                });
            },

            editarPedido(pedidoId) {
                const endpoint = this.rota_pedidos ? `${this.rota_pedidos}/${pedidoId}/edit` : `/vendas/pedidos/${pedidoId}/edit`;
                window.location.href = endpoint;
            },

            async excluirPedido(pedido_id) {
                try {
                    const endpoint = this.rota_pedidos ? `${this.rota_pedidos}/${pedido_id}/destroy` : `/vendas/pedidos/${pedido_id}/destroy`;
                    await axios.post(endpoint);

                    window.Toast.fire({
                        icon: 'success',
                        title: 'Pedido excluído com sucesso!'
                    });

                    await this.buscarPedidos();
                } catch (error) {
                    window.Toast.fire({
                       icon: 'error',
                       title: 'Não foi possível excluir o pedido.',
                       text: error.response?.message || 'Erro interno no servidor.'
                    });
                }
            }
        }
    }
</script>
