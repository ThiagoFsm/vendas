<script>
export default {
    name: "Configuracoes",
    props: ['rota_configuracoes', 'rota_salvar', 'rota_novo_item'],

    data() {
        return {
            abaAtiva: 'tipos',
            abas: [
                {
                    id: 'tipos',
                    titulo: 'Tipos de Produtos',
                    descricao: 'Gerencie quais categorias aparecem no cardápio.'
                },
                {
                    id: 'produtos',
                    titulo: 'Produtos',
                    descricao: 'Ative ou inative os produtos disponíveis para venda.'
                },
                {
                    id: 'sabores',
                    titulo: 'Sabores',
                    descricao: 'Gerencie quais sabores ficam ativos no catálogo.'
                },
                {
                    id: 'tamanhos',
                    titulo: 'Tamanhos',
                    descricao: 'Gerencie os tamanhos disponíveis para os produtos.'
                },
                {
                    id: 'valores',
                    titulo: 'Valores',
                    descricao: 'Atualize os valores de venda dos produtos cadastrados.'
                }
            ],

            carregando: false,
            salvando: false,
            criandoItem: false,
            modalNovoItem: false,

            termoBusca: '',
            filtroTipoProduto: '',

            tipoProdutos: [],
            produtos: [],
            sabores: [],
            tamanhos: [],

            snapshotOriginal: {
                tipoProdutos: {},
                produtos: {},
                sabores: {},
                tamanhos: {}
            },

            novoItem: {
                tipo_item: 'tipo_produto',
                descricao: '',
                tipo_produto_id: '',
                sabor_id: '',
                tamanho_id: '',
                valor_produto: '',
                ativo: true
            }
        };
    },

    async mounted() {
        await this.carregarDados();
    },

    computed: {
        abaAtual() {
            return this.abas.find(aba => aba.id === this.abaAtiva) || this.abas[0];
        },

        placeholderNovoItem() {
            if (this.novoItem.tipo_item === 'tipo_produto') return 'Ex.: Bolos Caseiros';
            if (this.novoItem.tipo_item === 'sabor') return 'Ex.: Torta de Morango';
            if (this.novoItem.tipo_item === 'tamanho') return 'Ex.: Travessa M';
            return '';
        },

        produtosFiltrados() {
            return this.produtos.filter(produto => {
                if (this.filtroTipoProduto && String(produto.tipo_produto_id) !== String(this.filtroTipoProduto)) {
                    return false;
                }

                if (this.termoBusca && this.termoBusca.trim()) {
                    const termo = this.normalizarTexto(this.termoBusca);
                    const tipo = this.normalizarTexto(produto.tipo_produto?.descricao);
                    const sabor = this.normalizarTexto(produto.sabor?.descricao);
                    const tamanho = this.normalizarTexto(produto.tamanho?.descricao);

                    if (!tipo.includes(termo) && !sabor.includes(termo) && !tamanho.includes(termo)) {
                        return false;
                    }
                }

                return true;
            });
        },

        saboresFiltrados() {
            if (!this.termoBusca || !this.termoBusca.trim()) {
                return this.sabores;
            }

            const termo = this.normalizarTexto(this.termoBusca);
            return this.sabores.filter(sabor => this.normalizarTexto(sabor.descricao).includes(termo));
        },

        alteracoesPendentes() {
            const tipoProdutos = this.tipoProdutos
                .filter(item => Boolean(item.ativo) !== Boolean(this.snapshotOriginal.tipoProdutos[item.id]))
                .map(item => ({ id: item.id, ativo: Boolean(item.ativo) }));

            const sabores = this.sabores
                .filter(item => Boolean(item.ativo) !== Boolean(this.snapshotOriginal.sabores[item.id]))
                .map(item => ({ id: item.id, ativo: Boolean(item.ativo) }));

            const tamanhos = this.tamanhos
                .filter(item => Boolean(item.ativo) !== Boolean(this.snapshotOriginal.tamanhos[item.id]))
                .map(item => ({ id: item.id, ativo: Boolean(item.ativo) }));

            const produtos = [];
            this.produtos.forEach(item => {
                const original = this.snapshotOriginal.produtos[item.id];
                if (!original) return;

                const ativoAtual = Boolean(item.ativo);
                const valorAtual = this.parseValor(item.valor_editavel);
                const ativoMudou = ativoAtual !== Boolean(original.ativo);
                const valorMudou = valorAtual !== null && Math.abs(valorAtual - original.valor) > 0.001;

                if (ativoMudou || valorMudou) {
                    const payload = { id: item.id };
                    if (ativoMudou) payload.ativo = ativoAtual;
                    if (valorMudou) payload.valor_produto = item.valor_editavel;
                    produtos.push(payload);
                }
            });

            return { tipoProdutos, sabores, tamanhos, produtos };
        },

        totalAlteracoesPendentes() {
            const p = this.alteracoesPendentes;
            return p.tipoProdutos.length + p.sabores.length + p.tamanhos.length + p.produtos.length;
        }
    },

    methods: {
        normalizarTexto(texto) {
            return (texto || '')
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .trim();
        },

        formatarValor(valor) {
            const num = parseFloat(valor);
            if (Number.isNaN(num)) return '0,00';
            return num.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },

        parseValor(valor) {
            if (typeof valor === 'number') return valor;
            if (typeof valor !== 'string') return null;

            const limpo = valor.replace(/R\$/g, '').replace(/\s+/g, '').trim();
            if (!limpo) return null;

            const normalizado = limpo.includes(',')
                ? limpo.replace(/\./g, '').replace(',', '.')
                : limpo;

            const numero = parseFloat(normalizado);
            return Number.isNaN(numero) ? null : numero;
        },

        selecionarAba(abaId) {
            this.abaAtiva = abaId;
            this.termoBusca = '';
            this.filtroTipoProduto = '';
        },

        aplicarDados(dados) {
            this.tipoProdutos = (dados.tipoProdutos || []).map(item => ({
                ...item,
                ativo: Boolean(item.ativo)
            }));

            this.sabores = (dados.sabores || []).map(item => ({
                ...item,
                ativo: Boolean(item.ativo)
            }));

            this.tamanhos = (dados.tamanhos || []).map(item => ({
                ...item,
                ativo: Boolean(item.ativo)
            }));

            this.produtos = (dados.produtos || []).map(item => ({
                ...item,
                ativo: Boolean(item.ativo),
                valor_editavel: this.formatarValor(item.valor_produto)
            }));

            const snap = {
                tipoProdutos: {},
                produtos: {},
                sabores: {},
                tamanhos: {}
            };

            this.tipoProdutos.forEach(item => {
                snap.tipoProdutos[item.id] = Boolean(item.ativo);
            });
            this.sabores.forEach(item => {
                snap.sabores[item.id] = Boolean(item.ativo);
            });
            this.tamanhos.forEach(item => {
                snap.tamanhos[item.id] = Boolean(item.ativo);
            });
            this.produtos.forEach(item => {
                snap.produtos[item.id] = {
                    ativo: Boolean(item.ativo),
                    valor: parseFloat(item.valor_produto) || 0
                };
            });

            this.snapshotOriginal = snap;
        },

        async carregarDados() {
            this.carregando = true;
            try {
                const endpoint = this.rota_configuracoes || '/vendas/configuracoes';
                const response = await axios.get(endpoint);
                if (response.data) {
                    this.aplicarDados(response.data);
                }
            } catch (error) {
                window.Toast.fire({
                    icon: 'error',
                    title: 'Erro ao carregar as configurações do catálogo.'
                });
            } finally {
                this.carregando = false;
            }
        },

        async salvarAlteracoes() {
            if (this.totalAlteracoesPendentes === 0) {
                window.Toast.fire({
                    icon: 'info',
                    title: 'Nenhuma alteração pendente para salvar.'
                });
                return;
            }

            this.salvando = true;
            try {
                const endpoint = this.rota_salvar || '/vendas/configuracoes/salvar';
                const response = await axios.post(endpoint, this.alteracoesPendentes);

                if (response.data?.dados) {
                    this.aplicarDados(response.data.dados);
                }

                window.Toast.fire({
                    icon: 'success',
                    title: response.data?.message || 'Alterações salvas com sucesso!'
                });
            } catch (error) {
                const msg = error.response?.data?.message || 'Erro ao salvar as alterações.';
                window.Toast.fire({
                    icon: 'error',
                    title: msg
                });
            } finally {
                this.salvando = false;
            }
        },

        abrirModalNovoItem() {
            const mapaAbaParaTipo = {
                tipos: 'tipo_produto',
                produtos: 'produto',
                sabores: 'sabor',
                tamanhos: 'tamanho',
                valores: 'produto'
            };

            this.novoItem = {
                tipo_item: mapaAbaParaTipo[this.abaAtiva] || 'tipo_produto',
                descricao: '',
                tipo_produto_id: '',
                sabor_id: '',
                tamanho_id: '',
                valor_produto: '',
                ativo: true
            };
            this.modalNovoItem = true;
        },

        fecharModalNovoItem() {
            this.modalNovoItem = false;
            this.criandoItem = false;
        },

        async salvarNovoItem() {
            if (this.novoItem.tipo_item === 'produto') {
                if (!this.novoItem.tipo_produto_id || !this.novoItem.sabor_id || !this.novoItem.tamanho_id || !this.novoItem.valor_produto) {
                    window.Toast.fire({
                        icon: 'warning',
                        title: 'Preencha todos os campos obrigatórios do produto.'
                    });
                    return;
                }
            } else if (!this.novoItem.descricao || !this.novoItem.descricao.trim()) {
                window.Toast.fire({
                    icon: 'warning',
                    title: 'Informe a descrição do item.'
                });
                return;
            }

            this.criandoItem = true;
            try {
                const endpoint = this.rota_novo_item || '/vendas/configuracoes/novo-item';
                const response = await axios.post(endpoint, this.novoItem);

                if (response.data?.dados) {
                    this.aplicarDados(response.data.dados);
                }

                window.Toast.fire({
                    icon: 'success',
                    title: response.data?.message || 'Item cadastrado com sucesso!'
                });

                this.fecharModalNovoItem();
            } catch (error) {
                const msg = error.response?.data?.message || 'Erro ao cadastrar item.';
                window.Toast.fire({
                    icon: 'error',
                    title: msg
                });
            } finally {
                this.criandoItem = false;
            }
        }
    }
};
</script>
