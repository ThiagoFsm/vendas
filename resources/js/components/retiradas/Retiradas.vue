<script>
export default {
    name: "Retiradas",
    props: ['rota_retiradas', 'rota_pedidos', 'rota_dependencias'],

    data() {
        return {
            retiradas: [],
            carregando: false,
            salvando: false,

            // Dependências
            dependencias: {
                bairros: []
            },

            // Filtros e busca
            termoPesquisa: '',
            filtroBairro: '',
            filtroPeriodo: '',
            filtroData: '',
            filtroStatusPedido: '',

            // Modal de Detalhes
            modal: false,
            dadosModal: {},

            // Modal de Edição
            modalEditar: false,
            retiradaEditando: null,
            formEditar: {
                id: null,
                data: '',
                periodo: '',
                bairro: ''
            }
        };
    },

    async mounted() {
        await Promise.all([
            this.buscarRetiradas(),
            this.carregarDependencias()
        ]);
    },

    computed: {
        bairrosUnicos() {
            const conjunto = new Set();
            if (Array.isArray(this.retiradas)) {
                this.retiradas.forEach(r => {
                    if (r.bairro && r.bairro.trim()) {
                        conjunto.add(r.bairro.trim());
                    }
                });
            }
            if (this.dependencias.bairros && Array.isArray(this.dependencias.bairros)) {
                this.dependencias.bairros.forEach(b => {
                    if (b.descricao && b.descricao.trim()) {
                        conjunto.add(b.descricao.trim());
                    }
                });
            }
            return Array.from(conjunto).sort((a, b) => a.localeCompare(b));
        },

        hasFiltrosAtivos() {
            return Boolean(
                this.termoPesquisa ||
                this.filtroBairro ||
                this.filtroPeriodo ||
                this.filtroData ||
                this.filtroStatusPedido
            );
        },

        retiradasFiltradas() {
            if (!this.retiradas || !Array.isArray(this.retiradas)) return [];

            return this.retiradas.filter(retirada => {
                // 1. Filtro por Termo de Busca (Cliente, Telefone, Bairro, IDs)
                if (this.termoPesquisa && this.termoPesquisa.trim()) {
                    const normalizar = (texto) => (texto || '')
                        .toString()
                        .toLowerCase()
                        .normalize('NFD')
                        .replace(/[\u0300-\u036f]/g, '');

                    const termo = normalizar(this.termoPesquisa.trim());

                    const nomeCliente = normalizar(retirada.pedido?.cliente?.nome);
                    const telefoneCliente = normalizar(retirada.pedido?.cliente?.telefone);
                    const bairro = normalizar(retirada.bairro);
                    const idRetirada = normalizar(retirada.id);
                    const idPedido = normalizar(retirada.pedido?.id);

                    const match =
                        nomeCliente.includes(termo) ||
                        telefoneCliente.includes(termo) ||
                        bairro.includes(termo) ||
                        idRetirada.includes(termo) ||
                        idPedido.includes(termo);

                    if (!match) {
                        return false;
                    }
                }

                // 2. Filtro por Bairro
                if (this.filtroBairro) {
                    if ((retirada.bairro || '').trim() !== this.filtroBairro.trim()) {
                        return false;
                    }
                }

                // 3. Filtro por Período (Manhã, Tarde, Noite)
                if (this.filtroPeriodo && retirada.periodo !== this.filtroPeriodo) {
                    return false;
                }

                // 4. Filtro por Data
                if (this.filtroData) {
                    if (!retirada.data || !retirada.data.startsWith(this.filtroData)) {
                        return false;
                    }
                }

                // 5. Filtro por Status do Pedido
                if (this.filtroStatusPedido) {
                    if (!retirada.pedido) return false;

                    if (this.filtroStatusPedido === 'pago' && !retirada.pedido.pago) {
                        return false;
                    }
                    if (this.filtroStatusPedido === 'pendente' && retirada.pedido.pago) {
                        return false;
                    }
                    if (this.filtroStatusPedido === 'produzido' && !retirada.pedido.produzido) {
                        return false;
                    }
                    if (this.filtroStatusPedido === 'a_fazer' && retirada.pedido.produzido) {
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
            this.filtroBairro = '';
            this.filtroPeriodo = '';
            this.filtroData = '';
            this.filtroStatusPedido = '';
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
                const endpoint = this.rota_dependencias || '/vendas/retiradas/dependencias';
                const response = await axios.get(endpoint);
                if (response.data) {
                    this.dependencias = response.data;
                }
            } catch (error) {
                console.error('Erro ao carregar dependências:', error);
            }
        },

        async buscarRetiradas() {
            this.carregando = true;
            try {
                const endpoint = this.rota_retiradas || '/vendas/retiradas';
                const response = await axios.get(endpoint);
                this.retiradas = response.data || [];
            } catch (error) {
                if (window.Toast) {
                    window.Toast.fire({
                        icon: 'error',
                        title: 'Erro ao carregar lista de retiradas.'
                    });
                }
            } finally {
                this.carregando = false;
            }
        },

        abrirModalDetalhes(retirada) {
            this.dadosModal = retirada;
            this.modal = true;
        },

        fecharModal() {
            this.modal = false;
            this.dadosModal = {};
        },

        abrirModalEditar(retirada) {
            this.retiradaEditando = retirada;
            this.formEditar = {
                id: retirada.id,
                data: retirada.data ? retirada.data.split('T')[0] : '',
                periodo: retirada.periodo || '',
                bairro: retirada.bairro || ''
            };
            this.modalEditar = true;
        },

        fecharModalEditar() {
            this.modalEditar = false;
            this.retiradaEditando = null;
            this.salvando = false;
        },

        async salvarEdicaoRetirada() {
            if (!this.formEditar.id) return;

            this.salvando = true;
            try {
                const endpoint = `/vendas/retiradas/${this.formEditar.id}/update`;
                const payload = {
                    data: this.formEditar.data,
                    periodo: this.formEditar.periodo || null,
                    bairro: this.formEditar.bairro
                };

                const response = await axios.post(endpoint, payload);

                if (window.Toast) {
                    window.Toast.fire({
                        icon: 'success',
                        title: response.data?.message || 'Retirada atualizada com sucesso!'
                    });
                }

                const retiradaAtualizada = response.data?.retirada;

                // Atualiza a retirada na listagem local
                const index = this.retiradas.findIndex(r => r.id === this.formEditar.id);
                if (index !== -1 && retiradaAtualizada) {
                    this.$set(this.retiradas, index, retiradaAtualizada);
                } else {
                    await this.buscarRetiradas();
                }

                // Se a modal de detalhes estiver aberta para esta mesma retirada, atualiza também
                if (this.dadosModal && this.dadosModal.id === this.formEditar.id && retiradaAtualizada) {
                    this.dadosModal = retiradaAtualizada;
                }

                this.fecharModalEditar();
            } catch (error) {
                const msg = error.response?.data?.message || 'Erro ao atualizar dados da retirada.';
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

                        const retiradaAtual = this.retiradas.find(r => r.pedido?.id === pedido_id);
                        if (retiradaAtual && retiradaAtual.pedido) {
                            this.$set(retiradaAtual.pedido, 'pago', true);
                            this.$set(retiradaAtual.pedido, 'valor_restante', 0);
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

                        const retiradaAtual = this.retiradas.find(r => r.pedido?.id === pedido_id);
                        if (retiradaAtual && retiradaAtual.pedido) {
                            this.$set(retiradaAtual.pedido, 'produzido', true);
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
