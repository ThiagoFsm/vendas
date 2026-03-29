<script>
export default {
    name: "criar-pedido",
    props: ['produtos', 'cliente', 'bairros', 'vendedores'],
    data() {
        return {
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
                valor_uber: 0.00
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

    mounted() {
        console.log(this.bairros)
    },

    watch: {
        valor_total_itens() {
            // melhoria: poder mudar o valor do pedido depois de colocar o valor do uber
            this.valor_total_pedido = this.valor_total_itens + this.uber.valor_uber;
        },

        'uber.valor_uber'() {
            setTimeout(() => {
                const valorString = String(this.uber.valor_uber);
                const uber = parseFloat(valorString.replace(/\./g, '').replace(',', '.'));
                this.valor_total_pedido = this.valor_total_itens + (isNaN(uber) ? 0 : uber);
            }, 1000)
        },

        entrega_retirada(novoValor) {
            this.resetarDadosEntrega(novoValor);
        },
    },

    methods: {
        adicionarItem() {
            if (this.itens_pedido.length === 0) {
                this.listar = true;
            }

            const pedido = this.produtos.find(item =>
                item.tipo_produto.id === Number(this.item.tipo_produto_id) &&
                item.sabor.id === Number(this.item.sabor_id) &&
                item.tamanho.id === Number(this.item.tamanho_id)
            );

            // if (!pedido)
            // produto não cadastrado (ex: coração lapidado 350g)

            const item = {
                produto_id: pedido.id,
                produto: pedido.tipo_produto.descricao,
                sabor: pedido.sabor.descricao,
                tamanho: pedido.tamanho.descricao,
                quantidade: this.item.quantidade,
                valor: Number(this.item.quantidade * pedido.valor_produto)
            };

            this.itens_pedido.push(item);
            this.valor_total_itens = this.valor_total_itens + Number(this.item.quantidade * pedido.valor_produto);
            this.limparCamposItem();
        },

        excluirItem(idExcluir) {
            const item = this.itens_pedido.find(item =>
                item.produto_id === Number(idExcluir)
            );
            this.valor_total_itens = this.valor_total_itens - Number(item.valor);

            this.itens_pedido = this.itens_pedido.filter(item =>
                item.produto_id !== Number(idExcluir)
            );

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
            return null
        },

        async salvarPedido() {
            try {
                const response = await axios.post('/vendas/pedidos/store', {
                    cliente: this.cliente,
                    pedido: this.itens_pedido,
                    entrega_retirada: this.retirada_entrega(),
                    valor_antecipado: this.valor_antecipado.replace(/\./g, '').replace(',', '.'),
                    valor_total: this.valor_total_pedido
                }). then(() => {
                        window.Toast.fire({ icon: 'success', title: 'Pedido criado com sucesso!' });
                    setTimeout(() => {
                        window.location.href = '/vendas/pedidos';
                    }, 3000);
                });

            } catch (error) {
                window.Toast.fire({ icon: 'error', title: 'Erro criar pedido.', text: error.message });
            }
        },

        resetarDadosEntrega(tipoSelecionado) {
            if (tipoSelecionado !== 'uber') {
                this.uber = { bairro_id: '', rua: '', numero: '', data: '', periodo: '', valor_uber: 0.00 };
            }
            if (tipoSelecionado !== 'entrega') {
                this.entrega = { entregador_id: '', data: '', periodo: '' };
            }
            if (tipoSelecionado !== 'retirada') {
                this.retirada = { bairro: '', data: '', periodo: '' };
            }
        },
    }
}
</script>
