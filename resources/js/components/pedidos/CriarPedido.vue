<script>
export default {
    name: "criar-pedido",
    props: ['pedidoId', 'clienteId'],
    data() {
        return {
            isEditing: false,
            isLoading: false,
            cliente: null,
            produtos: [],
            tipoProdutos: [],
            sabores: [],
            tamanhos: [],
            vendedores: [],
            bairros: [],
            item: {
                tipo_produto_id: '',
                sabor_id: '',
                tamanho_id: '',
                quantidade: ''
            },
            itens_pedido: [],
            listar: false,
            entrega_retirada: '',
            uber: {
                bairro_id: '',
                rua: '',
                numero: '',
                data: '',
                periodo: '',
                valor_uber: '0,00'
            },
            entrega: {
                entregador_id: '',
                data: '',
                periodo: ''
            },
            retirada: {
                bairro: '',
                data: '',
                periodo: ''
            },
            valor_antecipado: 0.00,
            valor_total_itens: 0.00,
            valor_total_pedido: 0.00
        }
    },

    async mounted() {
        await this.carregarDependencias();

        if (this.pedidoId) {
            this.isEditing = true;
            await this.carregarPedidoParaEdicao(this.pedidoId);
        }
    },

    watch: {
        valor_total_itens() {
            this.recalcularTotal();
        },

        'uber.valor_uber'() {
            this.recalcularTotal();
        },

        entrega_retirada(novoValor) {
            this.resetarDadosEntrega(novoValor);
            this.recalcularTotal();
        },
    },

    methods: {
        async carregarDependencias() {
            try {
                const endpoint = this.clienteId
                    ? `/vendas/pedidos/dependencias/${this.clienteId}`
                    : '/vendas/pedidos/dependencias';
                const response = await axios.get(endpoint);
                if (response.data) {
                    this.produtos = response.data.produtos || [];
                    this.tipoProdutos = response.data.tipoProdutos || [];
                    this.sabores = response.data.sabores || [];
                    this.tamanhos = response.data.tamanhos || [];
                    this.vendedores = response.data.vendedores || [];
                    this.bairros = response.data.bairros || [];
                    if (response.data.cliente) {
                        this.cliente = response.data.cliente;
                    }
                }
            } catch (error) {
                window.Toast.fire({ icon: 'error', title: 'Erro ao carregar dados do formulário.' });
            }
        },

        async carregarPedidoParaEdicao(id) {
            this.isLoading = true;
            try {
                const response = await axios.get(`/vendas/pedidos/${id}`);
                const pedido = response.data;
                if (!pedido) return;

                this.cliente = pedido.cliente;

                if (Array.isArray(pedido.produtos)) {
                    this.itens_pedido = pedido.produtos.map(p => {
                        const qtd = p.pivot ? Number(p.pivot.quantidade) : 1;
                        const unitVal = Number(p.valor_produto) || 0;
                        return {
                            produto_id: p.id,
                            produto: p.tipo_produto ? p.tipo_produto.descricao : (p.descricao || ''),
                            sabor: p.sabor ? p.sabor.descricao : '',
                            tamanho: p.tamanho ? p.tamanho.descricao : '',
                            quantidade: qtd,
                            produzido: p.pivot ? !!p.pivot.produzido : false,
                            valor: qtd * unitVal
                        };
                    });
                    this.listar = this.itens_pedido.length > 0;
                    this.valor_total_itens = this.itens_pedido.reduce((acc, item) => acc + Number(item.valor), 0);
                }

                this.valor_antecipado = pedido.valor_antecipado ?? 0;
                this.valor_total_pedido = pedido.valor_total ?? 0;

                const formatDate = (dateStr) => {
                    if (!dateStr) return '';
                    return dateStr.split('T')[0];
                };

                if (pedido.entrega_retirada) {
                    const er = pedido.entrega_retirada;
                    if (er.valor_uber > 0 || (er.bairro_id && er.rua)) {
                        this.entrega_retirada = 'uber';
                        this.uber = {
                            bairro_id: er.bairro_id || '',
                            rua: er.rua || '',
                            numero: er.numero || '',
                            data: formatDate(er.data),
                            periodo: er.periodo || '',
                            valor_uber: er.valor_uber || '0,00'
                        };
                    } else if (er.entregador_id) {
                        this.entrega_retirada = 'entrega';
                        this.entrega = {
                            entregador_id: er.entregador_id || '',
                            data: formatDate(er.data),
                            periodo: er.periodo || ''
                        };
                    } else {
                        this.entrega_retirada = 'retirada';
                        this.retirada = {
                            bairro: er.bairro || '',
                            data: formatDate(er.data),
                            periodo: er.periodo || ''
                        };
                    }
                }
            } catch (error) {
                window.Toast.fire({ icon: 'error', title: 'Erro ao carregar dados do pedido.' });
            } finally {
                this.$nextTick(() => {
                    this.isLoading = false;
                    this.recalcularTotal();
                });
            }
        },

        recalcularTotal() {
            let valorUber = 0;
            if (this.entrega_retirada === 'uber' && this.uber.valor_uber) {
                if (typeof this.uber.valor_uber === 'number') {
                    valorUber = this.uber.valor_uber;
                } else {
                    const valorString = String(this.uber.valor_uber);
                    valorUber = parseFloat(valorString.replace(/\./g, '').replace(',', '.')) || 0;
                }
            }
            this.valor_total_pedido = (Number(this.valor_total_itens) || 0) + valorUber;
        },

        adicionarItem() {
            if (!this.item.tipo_produto_id || !this.item.sabor_id || !this.item.tamanho_id || !this.item.quantidade) {
                window.Toast.fire({ icon: 'warning', title: 'Preencha todos os campos do item.' });
                return;
            }

            const produtoEncontrado = this.produtos.find(p =>
                Number(p.tipo_produto_id || p.tipo_produto?.id) === Number(this.item.tipo_produto_id) &&
                Number(p.sabor_id || p.sabor?.id) === Number(this.item.sabor_id) &&
                Number(p.tamanho_id || p.tamanho?.id) === Number(this.item.tamanho_id)
            );

            if (!produtoEncontrado) {
                window.Toast.fire({ icon: 'error', title: 'Produto com esta combinação não encontrado no catálogo.' });
                return;
            }

            const item = {
                produto_id: produtoEncontrado.id,
                produto: produtoEncontrado.tipo_produto ? produtoEncontrado.tipo_produto.descricao : '',
                sabor: produtoEncontrado.sabor ? produtoEncontrado.sabor.descricao : '',
                tamanho: produtoEncontrado.tamanho ? produtoEncontrado.tamanho.descricao : '',
                quantidade: Number(this.item.quantidade),
                valor: Number(this.item.quantidade) * Number(produtoEncontrado.valor_produto),
                produzido: false
            };

            this.itens_pedido.push(item);
            this.valor_total_itens += item.valor;
            this.listar = true;
            this.limparCamposItem();
        },

        excluirItem(index) {
            const item = this.itens_pedido[index];
            if (item) {
                this.valor_total_itens -= Number(item.valor);
                this.itens_pedido.splice(index, 1);
            }

            if (this.itens_pedido.length === 0) {
                this.listar = false;
            }
        },

        limparCamposItem() {
            this.item.tipo_produto_id = '';
            this.item.sabor_id = '';
            this.item.tamanho_id = '';
            this.item.quantidade = '';
        },

        retirada_entrega() {
            if (this.entrega_retirada === 'uber') return this.uber;
            if (this.entrega_retirada === 'retirada') return this.retirada;
            if (this.entrega_retirada === 'entrega') return this.entrega;
            return null;
        },

        resetarDadosEntrega(tipoSelecionado) {
            if (this.isLoading) return;
            if (tipoSelecionado !== 'uber') {
                this.uber = { bairro_id: '', rua: '', numero: '', data: '', periodo: '', valor_uber: '0,00' };
            }
            if (tipoSelecionado !== 'entrega') {
                this.entrega = { entregador_id: '', data: '', periodo: '' };
            }
            if (tipoSelecionado !== 'retirada') {
                this.retirada = { bairro: '', data: '', periodo: '' };
            }
        },

        async salvarPedido() {
            if (!this.cliente || !this.cliente.id) {
                window.Toast.fire({ icon: 'warning', title: 'Cliente não identificado.' });
                return;
            }

            if (this.itens_pedido.length === 0) {
                window.Toast.fire({ icon: 'warning', title: 'Adicione pelo menos um item ao pedido.' });
                return;
            }

            let valorAntecipado = this.valor_antecipado;
            if (typeof valorAntecipado === 'string') {
                valorAntecipado = parseFloat(valorAntecipado.replace(/\./g, '').replace(',', '.')) || 0;
            }

            try {
                const payload = {
                    pedidoId: this.pedidoId || null,
                    cliente: this.cliente,
                    pedido: this.itens_pedido,
                    entrega_retirada: this.retirada_entrega(),
                    valor_antecipado: valorAntecipado,
                    valor_total: this.valor_total_pedido
                };

                const url = this.isEditing && this.pedidoId
                    ? `/vendas/pedidos/store/${this.pedidoId}`
                    : '/vendas/pedidos/store';

                await axios.post(url, payload);

                const msgSucesso = this.isEditing
                    ? 'Pedido atualizado com sucesso!'
                    : 'Pedido criado com sucesso!';

                window.Toast.fire({ icon: 'success', title: msgSucesso });
                setTimeout(() => {
                    window.location.href = '/vendas/pedidos';
                }, 1500);

            } catch (error) {
                window.Toast.fire({
                    icon: 'error',
                    title: this.isEditing ? 'Erro ao atualizar pedido.' : 'Erro ao criar pedido.',
                    text: error.response?.data?.message || error.message
                });
            }
        }
    }
}
</script>
