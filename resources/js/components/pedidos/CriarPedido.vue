<script>
export default {
    name: "criar-pedido",
    props: ['produtos', 'cliente'],
    data() {
        return {
            bairros: {
                id: '',
                nome: ''
            },
            item: {
                tipo_produto_id: '',
                sabor_id: '',
                tamanho_id: '',
                quantidade: ''
            },
            itens_pedido: [],
            listar: false,
            uber_selecionado: '',
            uber: {
                bairro_id: '',
                rua: '',
                numero: '',
                data: '',
                periodo: '',
                valor_uber: 0.00
            },
            entrega_selecionada: '',
            entrega: {
                entregador_id: '',
                data: '',
                periodo: ''
            },
            retirada_selecionada: '',
            retirada: {
                bairro_id: '',
                data: '',
                periodo: ''
            },
            valor_antecipado: 0.00,
            valor_total_itens: 0.00,
            valor_total_pedido: 0.00
        }
    },

    mounted() {
        // console.log(this.produtos)
    },

    watch: {
        uber_selecionado() {
            if (this.uber_selecionado) {
                this.entrega_selecionada = 0;
                this.retirada_selecionada = 0;
            }
        },

        retirada_selecionada() {
            if (this.retirada_selecionada) {
                this.uber_selecionado = 0;
                this.entrega_selecionada = 0;
            }
        },

        entrega_selecionada() {
            if (this.entrega_selecionada) {
                this.uber_selecionado = 0;
                this.retirada_selecionada = 0;
            }
        },

        valor_total_itens() {
            // melhoria: poder mudar o valor do pedido depois de colocar o valor do uber
            this.valor_total_pedido = this.valor_total_itens + this.uber.valor_uber;
        },

        'uber.valor_uber'() {
            setTimeout(() => {
                const uber = parseFloat(this.uber.valor_uber.replace(/\./g, '').replace(',', '.'));
                this.valor_total_pedido = this.valor_total_itens + uber;
            }, 1000)
        }
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

        async salvarPedido() {
            const retirada_entrega = () => {
                if (this.uber_selecionado) return this.uber;
                if (this.retirada_selecionada) return this.retirada;
                if (this.entrega_selecionada) return this.entrega;
                return null
            }

            try {
                const response = await axios.post('/vendas/pedidos/store', {
                    cliente: this.cliente,
                    pedido: this.itens_pedido,
                    entrega_retirada: retirada_entrega(),
                    valor_antecipado: this.valor_antecipado.replace(/\./g, '').replace(',', '.'),
                    valor_total: this.valor_total_pedido
                });
                if (response.status === 200 || response.status === 201) {
                    console.log(response.data.message)
                    // this.$toast.success(response)
                    // window.location.href('vendas.pedidos') // redirecionar para pedidos index
                }
            } catch (error) {
                console.log("erro")
                // console.error('Erro ao salvar pedido:', error.response.data);
                // alert('Erro ao salvar:' + error.response.data.message);
            }
        }
    }
}
</script>
