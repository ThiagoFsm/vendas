<script>
export default {
    name: "Entregas",
    props: ['rota_entregas', 'rota_pedidos', 'rota_dependencias'],

    data() {
        return {
            entregas: [],
            carregando: false,
            salvando: false,

            // Dependências (bairros e entregadores)
            dependencias: {
                bairros: [],
                entregadores: []
            },

            // Filtros e busca
            termoPesquisa: '',
            filtroTipo: '',
            filtroPeriodo: '',
            filtroData: '',
            filtroStatusPedido: '',

            // Modal de Detalhes
            modal: false,
            dadosModal: {},

            // Modal de Edição
            modalEditar: false,
            entregaEditando: null,
            formEditar: {
                id: null,
                data: '',
                periodo: '',
                entregador_id: '',
                bairro_id: '',
                rua: '',
                numero: '',
                valor_uber: ''
            }
        };
    },

    async mounted() {
        await Promise.all([
            this.buscarEntregas(),
            this.carregarDependencias()
        ]);
    },

    computed: {
        hasFiltrosAtivos() {
            return Boolean(
                this.termoPesquisa ||
                this.filtroTipo ||
                this.filtroPeriodo ||
                this.filtroData ||
                this.filtroStatusPedido
            );
        },

        entregasFiltradas() {
            if (!this.entregas || !Array.isArray(this.entregas)) return [];

            return this.entregas.filter(entrega => {
                // 1. Filtro por Termo de Busca (Cliente, Telefone, Entregador, Endereço, IDs)
                if (this.termoPesquisa && this.termoPesquisa.trim()) {
                    const normalizar = (texto) => (texto || '')
                        .toString()
                        .toLowerCase()
                        .normalize('NFD')
                        .replace(/[\u0300-\u036f]/g, '');

                    const termo = normalizar(this.termoPesquisa.trim());

                    const nomeCliente = normalizar(entrega.pedido?.cliente?.nome);
                    const telefoneCliente = normalizar(entrega.pedido?.cliente?.telefone);
                    const nomeEntregador = normalizar(entrega.entregador?.nome);
                    const rua = normalizar(entrega.rua);
                    const bairro = normalizar(entrega.bairro?.descricao);
                    const idEntrega = normalizar(entrega.id);
                    const idPedido = normalizar(entrega.pedido?.id);

                    const match =
                        nomeCliente.includes(termo) ||
                        telefoneCliente.includes(termo) ||
                        nomeEntregador.includes(termo) ||
                        rua.includes(termo) ||
                        bairro.includes(termo) ||
                        idEntrega.includes(termo) ||
                        idPedido.includes(termo);

                    if (!match) {
                        return false;
                    }
                }

                // 2. Filtro por Tipo de Entrega (Uber vs Entregador)
                if (this.filtroTipo) {
                    const tipo = this.tipoEntrega(entrega);
                    if (tipo !== this.filtroTipo) {
                        return false;
                    }
                }

                // 3. Filtro por Período (Manhã, Tarde, Noite)
                if (this.filtroPeriodo && entrega.periodo !== this.filtroPeriodo) {
                    return false;
                }

                // 4. Filtro por Data
                if (this.filtroData) {
                    if (!entrega.data || !entrega.data.startsWith(this.filtroData)) {
                        return false;
                    }
                }

                // 5. Filtro por Status do Pedido
                if (this.filtroStatusPedido) {
                    if (!entrega.pedido) return false;

                    if (this.filtroStatusPedido === 'pago' && !entrega.pedido.pago) {
                        return false;
                    }
                    if (this.filtroStatusPedido === 'pendente' && entrega.pedido.pago) {
                        return false;
                    }
                    if (this.filtroStatusPedido === 'produzido' && !entrega.pedido.produzido) {
                        return false;
                    }
                    if (this.filtroStatusPedido === 'a_fazer' && entrega.pedido.produzido) {
                        return false;
                    }
                }

                return true;
            });
        }
    },

    methods: {
        limparFiltros() {
            this.termoPesquisa = '';
            this.filtroTipo = '';
            this.filtroPeriodo = '';
            this.filtroData = '';
            this.filtroStatusPedido = '';
        },

        tipoEntrega(entrega) {
            if (!entrega) return 'entregador';
            if (entrega.valor_uber || (!entrega.entregador_id && (entrega.rua || entrega.bairro_id))) {
                return 'uber';
            }
            return 'entregador';
        },

        enderecoCompleto(entrega) {
            if (!entrega) return '-';
            const partes = [];
            if (entrega.rua) {
                let ruaCompleta = entrega.rua;
                if (entrega.numero) {
                    ruaCompleta += `, Nº ${entrega.numero}`;
                }
                partes.push(ruaCompleta);
            }
            if (entrega.bairro?.descricao) {
                partes.push(entrega.bairro.descricao);
            }

            return partes.length > 0 ? partes.join(' - ') : 'Endereço não informado';
        },

        formatarData(dataStr) {
            if (!dataStr) return '-';
            if (typeof dataStr === 'string' && dataStr.includes('-')) {
                const partes = dataStr.split('T')[0].split('-');
                if (partes.length === 3) {
                    return `${partes[2]}/${partes[1]}/${partes[0]}`;
                }
            }
            const data = new Date(dataStr);
            return isNaN(data.getTime()) ? dataStr : data.toLocaleDateString('pt-BR');
        },

        formatarValor(valor) {
            if (valor === null || valor === undefined || valor === '') return 'R$ 0,00';
            const num = parseFloat(valor) || 0;
            return num.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        },

        async carregarDependencias() {
            try {
                const endpoint = this.rota_dependencias || '/vendas/entregas/dependencias';
                const response = await axios.get(endpoint);
                if (response.data) {
                    this.dependencias = response.data;
                }
            } catch (error) {
                console.error('Erro ao carregar dependências:', error);
            }
        },

        async buscarEntregas() {
            this.carregando = true;
            try {
                const endpoint = this.rota_entregas || '/vendas/entregas';
                const response = await axios.get(endpoint);
                this.entregas = response.data || [];
            } catch (error) {
                if (window.Toast) {
                    window.Toast.fire({
                        icon: 'error',
                        title: 'Erro ao carregar lista de entregas.'
                    });
                }
            } finally {
                this.carregando = false;
            }
        },

        abrirModalDetalhes(entrega) {
            this.dadosModal = entrega;
            this.modal = true;
        },

        fecharModal() {
            this.modal = false;
            this.dadosModal = {};
        },

        abrirModalEditar(entrega) {
            this.entregaEditando = entrega;
            this.formEditar = {
                id: entrega.id,
                data: entrega.data ? entrega.data.split('T')[0] : '',
                periodo: entrega.periodo || '',
                entregador_id: entrega.entregador_id || '',
                bairro_id: entrega.bairro_id || '',
                rua: entrega.rua || '',
                numero: entrega.numero || '',
                valor_uber: entrega.valor_uber !== null && entrega.valor_uber !== undefined ? String(entrega.valor_uber) : ''
            };
            this.modalEditar = true;
        },

        fecharModalEditar() {
            this.modalEditar = false;
            this.entregaEditando = null;
            this.salvando = false;
        },

        async salvarEdicaoEntrega() {
            if (!this.formEditar.id) return;

            this.salvando = true;
            try {
                const endpoint = `/vendas/entregas/${this.formEditar.id}/update`;
                const payload = {
                    data: this.formEditar.data || null,
                    periodo: this.formEditar.periodo || null,
                    entregador_id: this.formEditar.entregador_id || null,
                    bairro_id: this.formEditar.bairro_id || null,
                    rua: this.formEditar.rua || null,
                    numero: this.formEditar.numero || null,
                    valor_uber: this.formEditar.valor_uber || null
                };

                const response = await axios.post(endpoint, payload);

                if (window.Toast) {
                    window.Toast.fire({
                        icon: 'success',
                        title: response.data?.message || 'Entrega atualizada com sucesso!'
                    });
                }

                const entregaAtualizada = response.data?.entrega;

                // Atualiza a entrega na listagem local
                const index = this.entregas.findIndex(e => e.id === this.formEditar.id);
                if (index !== -1 && entregaAtualizada) {
                    this.$set(this.entregas, index, entregaAtualizada);
                } else {
                    await this.buscarEntregas();
                }

                // Se a modal de detalhes estiver aberta para esta mesma entrega, atualiza também
                if (this.dadosModal && this.dadosModal.id === this.formEditar.id && entregaAtualizada) {
                    this.dadosModal = entregaAtualizada;
                }

                this.fecharModalEditar();
            } catch (error) {
                const msg = error.response?.data?.message || 'Erro ao atualizar dados da entrega.';
                if (window.Toast) {
                    window.Toast.fire({
                        icon: 'error',
                        title: msg
                    });
                }
            } finally {
                this.salvando = false;
            }
        },

        marcarPedidoComoPago(pedido_id) {
            if (!pedido_id) return;

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

                        const endpoint = `/vendas/pedidos/${pedido_id}/pagar`;
                        const response = await axios.post(endpoint);

                        if (window.Toast) {
                            window.Toast.fire({
                                icon: 'success',
                                title: response?.data?.message || 'Pagamento confirmado!'
                            });
                        }

                        if (this.dadosModal?.pedido) {
                            this.$set(this.dadosModal.pedido, 'pago', true);
                            this.$set(this.dadosModal.pedido, 'valor_restante', 0);
                        }

                        const entregaAtual = this.entregas.find(e => e.pedido?.id === pedido_id);
                        if (entregaAtual && entregaAtual.pedido) {
                            this.$set(entregaAtual.pedido, 'pago', true);
                            this.$set(entregaAtual.pedido, 'valor_restante', 0);
                        }

                        Swal.close();
                    } catch (error) {
                        if (window.Toast) {
                            window.Toast.fire({
                                icon: 'error',
                                title: 'Erro ao atualizar pagamento do pedido.'
                            });
                        }
                    }
                }
            });
        },

        marcarPedidoComoFeito(pedido_id) {
            if (!pedido_id) return;

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

                        const endpoint = `/vendas/pedidos/${pedido_id}/produzir`;
                        const response = await axios.post(endpoint);

                        if (window.Toast) {
                            window.Toast.fire({
                                icon: 'success',
                                title: response?.data?.message || 'Pedido marcado como feito!'
                            });
                        }

                        if (this.dadosModal?.pedido) {
                            this.$set(this.dadosModal.pedido, 'produzido', true);
                            if (Array.isArray(this.dadosModal.pedido.produtos)) {
                                this.dadosModal.pedido.produtos.forEach(p => {
                                    if (p.pivot) {
                                        this.$set(p.pivot, 'produzido', true);
                                    }
                                });
                            }
                        }

                        const entregaAtual = this.entregas.find(e => e.pedido?.id === pedido_id);
                        if (entregaAtual && entregaAtual.pedido) {
                            this.$set(entregaAtual.pedido, 'produzido', true);
                        }

                        Swal.close();
                    } catch (error) {
                        if (window.Toast) {
                            window.Toast.fire({
                                icon: 'error',
                                title: 'Erro ao atualizar produção do pedido.'
                            });
                        }
                    }
                }
            });
        }
    }
};
</script>
