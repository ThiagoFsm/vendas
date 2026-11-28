<script>
    import { marcarComoFeito } from '../../funcoes/pedidos';
    export default {
        name: "Pedidos",
        props: ['rota_pedidos', 'rota_criar_cliente', 'rota_store'],
        data() {
            return {
                pedidos: [],
                termoPesquisa: '',
                filtroVendedor: '',
                filtroProducao: '',
                carregando: false,
                modal: false,
                dadosModal: []
            }
        },

        async mounted() {
            await this.buscarPedidos();
        },

        computed: {
            vendedores() {
                if (!this.pedidos || !Array.isArray(this.pedidos)) return [];
                const mapa = new Map();
                this.pedidos.forEach(p => {
                    if (p.cliente && p.cliente.vendedor && p.cliente.vendedor.id) {
                        mapa.set(p.cliente.vendedor.id, p.cliente.vendedor.nome);
                    }
                });
                return Array.from(mapa, ([id, nome]) => ({ id, nome })).sort((a, b) => a.nome.localeCompare(b.nome));
            },

            hasFiltrosAtivos() {
                return Boolean(this.termoPesquisa || this.filtroVendedor || this.filtroProducao);
            },

            pedidosFiltrados() {
                return this.pedidos.filter(pedido => {
                    // Filtro por Nome do Cliente
                    if (this.termoPesquisa && this.termoPesquisa.trim()) {
                        const normalizar = (texto) => {
                            return (texto || '')
                                .toLowerCase()
                                .normalize('NFD')
                                .replace(/[\u0300-\u036f]/g, '');
                        };

                        const termo = normalizar(this.termoPesquisa.trim());
                        const nomeCliente = normalizar(pedido.cliente?.nome);
                        if (!nomeCliente.includes(termo)) {
                            return false;
                        }
                    }

                    // Filtro por Vendedor
                    if (this.filtroVendedor) {
                        const vendedorId = pedido.cliente?.vendedor?.id;
                        if (String(vendedorId) !== String(this.filtroVendedor)) {
                            return false;
                        }
                    }

                    // Filtro por Produção
                    if (this.filtroProducao === 'feito' && !pedido.produzido) {
                        return false;
                    }
                    if (this.filtroProducao === 'a_fazer' && pedido.produzido) {
                        return false;
                    }

                    return true;
                });
            }
        },

        methods: {
            limparFiltros() {
                this.termoPesquisa = '';
                this.filtroVendedor = '';
                this.filtroProducao = '';
            },

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
                window.location.href = `${this.rota_criar_cliente}`;
            },

            async detalhesPedido(pedido_id) {
                try {
                    const endpoint = this.rota_pedidos ? `${this.rota_pedidos}/${pedido_id}` : `/vendas/pedidos/${pedido_id}`;
                    const response = await axios.get(endpoint);

                    const produtos = response?.data?.produtos || [];
                    const todosProdutosProduzidos = produtos.length > 0 &&
                        produtos.every(p => Boolean(p.pivot && p.pivot.produzido));

                    this.dadosModal = {
                        dados: {
                            valor_total: response?.data.valor_total,
                            valor_antecipado: response?.data.valor_antecipado,
                            valor_restante: response?.data.valor_restante,
                            pedido_id: response?.data.id
                        },
                        pago: Boolean(response?.data.pago),
                        produzido: Boolean(response?.data.produzido || todosProdutosProduzidos),
                        nome_cliente: response?.data.cliente?.nome,
                        entrega_retirada: response?.data.entrega_retirada,
                        produtos: produtos
                    };
                    if (this.dadosModal.entrega_retirada && this.dadosModal.entrega_retirada.data) {
                        const data = new Date(this.dadosModal.entrega_retirada.data);
                        this.dadosModal.entrega_retirada.data = data.toLocaleDateString('pt-BR');
                    }
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

                            await this.detalhesPedido(pedido_id);
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

                            await this.detalhesPedido(pedido_id);
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

            async marcarEntregaRetiradaComoRealizada(pedido_id, isRetirada = false) {
                const produtos = Array.isArray(this.dadosModal?.produtos) ? this.dadosModal.produtos : [];
                const todosItensProntos = produtos.length > 0
                    ? produtos.every(p => Boolean(p.pivot && p.pivot.produzido))
                    : Boolean(this.dadosModal?.produzido);
                const pedidoProduzido = Boolean(this.dadosModal?.produzido) && todosItensProntos;

                if (!pedidoProduzido) {
                    const acao = isRetirada ? 'marcar como retirado' : 'marcar como entregue';
                    window.Toast.fire({
                        icon: 'warning',
                        title: `Não é possível ${acao}: todos os itens do pedido precisam estar produzidos primeiro.`
                    });
                    return;
                }

                const titulo = isRetirada ? 'Marcar como retirado?' : 'Marcar como entregue?';
                const texto = isRetirada
                    ? 'Confirmar que este pedido foi retirado?'
                    : 'Confirmar que este pedido foi entregue?';

                Swal.fire({
                    title: titulo,
                    text: texto,
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

                            const endpoint = this.rota_pedidos
                                ? `${this.rota_pedidos}/${pedido_id}/entregar-retirar`
                                : `/vendas/pedidos/${pedido_id}/entregar-retirar`;
                            const response = await axios.post(endpoint);

                            window.Toast.fire({
                                icon: 'success',
                                title: response?.data?.message || (isRetirada ? 'Pedido marcado como retirado com sucesso!' : 'Pedido marcado como entregue com sucesso!')
                            });

                            await this.detalhesPedido(pedido_id);
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
