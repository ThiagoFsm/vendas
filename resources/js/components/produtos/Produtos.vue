<script>
export default {
    name: "Produtos",
    props: ['rota_produtos', 'rota_dependencias', 'rota_store'],

    data() {
        return {
            produtos: [],
            dependencias: {
                tipoProdutos: [],
                sabores: [],
                tamanhos: []
            },
            carregando: false,
            salvando: false,
            modal: false,
            editando: false,
            produtoIdEditando: null,

            // Filtros e busca
            termoPesquisa: '',
            filtroTipo: '',
            filtroSabor: '',
            filtroStatus: '',

            // Formulário
            form: {
                tipo_produto_id: '',
                sabor_id: '',
                tamanho_id: '',
                valor_produto: '',
                ativo: true
            }
        };
    },

    async mounted() {
        await Promise.all([
            this.carregarDependencias(),
            this.buscarProdutos()
        ]);
    },

    computed: {
        hasFiltrosAtivos() {
            return Boolean(this.termoPesquisa || this.filtroTipo || this.filtroSabor || this.filtroStatus);
        },

        produtosFiltrados() {
            if (!this.produtos || !Array.isArray(this.produtos)) return [];

            return this.produtos.filter(produto => {
                // 1. Filtro por Termo de Busca (tipo, sabor ou tamanho)
                if (this.termoPesquisa && this.termoPesquisa.trim()) {
                    const normalizar = (texto) => (texto || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                    const termo = normalizar(this.termoPesquisa.trim());

                    const tipo = normalizar(produto.tipo_produto?.descricao);
                    const sabor = normalizar(produto.sabor?.descricao);
                    const tamanho = normalizar(produto.tamanho?.descricao);

                    const match = tipo.includes(termo) || sabor.includes(termo) || tamanho.includes(termo);
                    if (!match) {
                        return false;
                    }
                }

                // 2. Filtro por Tipo de Produto
                if (this.filtroTipo && String(produto.tipo_produto_id) !== String(this.filtroTipo)) {
                    return false;
                }

                // 3. Filtro por Sabor
                if (this.filtroSabor && String(produto.sabor_id) !== String(this.filtroSabor)) {
                    return false;
                }

                // 4. Filtro por Status Ativo/Inativo
                if (this.filtroStatus === 'ativo' && !produto.ativo) {
                    return false;
                }
                if (this.filtroStatus === 'inativo' && produto.ativo) {
                    return false;
                }

                return true;
            });
        }
    },

    methods: {
        formatarValor(valor) {
            const num = parseFloat(valor) || 0;
            return num.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },

        limparFiltros() {
            this.termoPesquisa = '';
            this.filtroTipo = '';
            this.filtroSabor = '';
            this.filtroStatus = '';
        },

        async carregarDependencias() {
            try {
                const endpoint = this.rota_dependencias || '/vendas/produtos/dependencias';
                const response = await axios.get(endpoint);
                if (response.data) {
                    this.dependencias = response.data;
                }
            } catch (error) {
                window.Toast.fire({
                    icon: 'error',
                    title: 'Erro ao carregar opções de tipo, sabor e tamanho.'
                });
            }
        },

        async buscarProdutos() {
            this.carregando = true;
            try {
                const endpoint = this.rota_produtos || '/vendas/produtos';
                const response = await axios.get(endpoint);
                this.produtos = response.data || [];
            } catch (error) {
                window.Toast.fire({
                    icon: 'error',
                    title: 'Erro ao carregar lista de produtos.'
                });
            } finally {
                this.carregando = false;
            }
        },

        abrirModalCriar() {
            this.editando = false;
            this.produtoIdEditando = null;
            this.form = {
                tipo_produto_id: '',
                sabor_id: '',
                tamanho_id: '',
                valor_produto: '',
                ativo: true
            };
            this.modal = true;
        },

        abrirModalEditar(produto) {
            this.editando = true;
            this.produtoIdEditando = produto.id;
            this.form = {
                tipo_produto_id: produto.tipo_produto_id,
                sabor_id: produto.sabor_id,
                tamanho_id: produto.tamanho_id,
                valor_produto: this.formatarValor(produto.valor_produto),
                ativo: Boolean(produto.ativo)
            };
            this.modal = true;
        },

        fecharModal() {
            this.modal = false;
            this.salvando = false;
        },

        async salvarProduto() {
            if (!this.form.tipo_produto_id || !this.form.sabor_id || !this.form.tamanho_id || !this.form.valor_produto) {
                window.Toast.fire({
                    icon: 'warning',
                    title: 'Preencha todos os campos obrigatórios.'
                });
                return;
            }

            this.salvando = true;

            try {
                const payload = {
                    tipo_produto_id: this.form.tipo_produto_id,
                    sabor_id: this.form.sabor_id,
                    tamanho_id: this.form.tamanho_id,
                    valor_produto: this.form.valor_produto,
                    ativo: this.form.ativo
                };

                let response;
                if (this.editando && this.produtoIdEditando) {
                    response = await axios.post(`/vendas/produtos/${this.produtoIdEditando}/update`, payload);
                } else {
                    const endpoint = this.rota_store || '/vendas/produtos/store';
                    response = await axios.post(endpoint, payload);
                }

                window.Toast.fire({
                    icon: 'success',
                    title: response.data?.message || 'Produto salvo com sucesso!'
                });

                this.fecharModal();
                await this.buscarProdutos();

            } catch (error) {
                const msg = error.response?.data?.message || 'Erro ao salvar produto.';
                window.Toast.fire({
                    icon: 'error',
                    title: msg
                });
            } finally {
                this.salvando = false;
            }
        },

        async toggleAtivo(produto) {
            try {
                const response = await axios.post(`/vendas/produtos/${produto.id}/toggle-ativo`);
                produto.ativo = response.data?.ativo ?? !produto.ativo;

                window.Toast.fire({
                    icon: 'success',
                    title: response.data?.message || 'Status alterado com sucesso!'
                });
            } catch (error) {
                window.Toast.fire({
                    icon: 'error',
                    title: 'Não foi possível alterar o status do produto.'
                });
            }
        },

        async excluirProduto(produto) {
            Swal.fire({
                title: 'Excluir produto?',
                text: `Deseja realmente excluir o produto #${produto.id} (${produto.tipo_produto?.descricao} - ${produto.sabor?.descricao})?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sim, excluir',
                cancelButtonText: 'Cancelar'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await axios.post(`/vendas/produtos/${produto.id}/destroy`);

                        window.Toast.fire({
                            icon: response.data?.action === 'deactivated' ? 'info' : 'success',
                            title: response.data?.message || 'Produto processado com sucesso!'
                        });

                        await this.buscarProdutos();

                    } catch (error) {
                        window.Toast.fire({
                            icon: 'error',
                            title: 'Erro ao excluir produto.',
                            text: error.response?.data?.message || error.message
                        });
                    }
                }
            });
        }
    }
};
</script>
