<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PedidosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->route('pedidoId')) {
            $this->merge([
                'pedidoId' => $this->route('pedidoId'),
            ]);
        }

        if ($this->has('valor_antecipado') && is_string($this->valor_antecipado) && trim($this->valor_antecipado) !== '') {
            $this->merge([
                'valor_antecipado' => (float) str_replace(',', '.', str_replace('.', '', $this->valor_antecipado)),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'pedidoId'                              => 'sometimes|nullable|exists:pedidos,id',
            'cliente'                               => 'sometimes|array',
                'cliente.id'                        => 'integer|exists:clientes,id',
                'cliente.nome'                      => 'string',
                'cliente.vendedor_id'               => 'integer|exists:vendedores,id',
            'pedido'                                => 'sometimes|array',
                'pedido.*.produto_id'               => 'integer|exists:produtos,id',
                'pedido.*.produto'                  => 'string|exists:tipo_produtos,descricao',
                'pedido.*.sabor'                    => 'string|exists:sabores,descricao',
                'pedido.*.tamanho'                  => 'string|exists:tamanhos,descricao',
                'pedido.*.quantidade'               => 'numeric|min:1',
                'pedido.*.valor'                    => 'numeric',
            'entrega_retirada'                      => 'sometimes|nullable|array',
                'entrega_retirada.data'             => 'sometimes|nullable|date',
                'entrega_retirada.periodo'          => 'sometimes|nullable|string',
                'entrega_retirada.horas'            => 'sometimes|nullable|string',
                'entrega_retirada.realizada'        => 'sometimes|nullable|boolean',
            // retirada
                'entrega_retirada.bairro'           => 'sometimes|nullable|string',
            // uber
                'entrega_retirada.bairro_id'        => 'sometimes|nullable|integer|exists:bairros,id',
                'entrega_retirada.rua'              => 'sometimes|nullable|string',
                'entrega_retirada.numero'           => 'sometimes|nullable|string',
                'entrega_retirada.valor_uber'       => 'sometimes|nullable',
            // entrega
                'entrega_retirada.entregador_id'    => 'sometimes|nullable|integer|exists:vendedores,id',
            'valor_antecipado'                      => 'sometimes|nullable|numeric',
            'valor_total'                           => 'sometimes|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            // Identificador geral
            'pedidoId.exists'                          => 'O pedido informado não foi encontrado no sistema.',

            // Cliente
            'cliente.id.exists'                         => 'O cliente informado não foi encontrado.',
            'cliente.vendedor_id.exists'                => 'O vendedor associado ao cliente não foi encontrado.',

            // Itens do Pedido
            'pedido.*.produto_id.exists'                => 'O produto informado não existe no catálogo.',
            'pedido.*.produto.nome.exists'              => 'O tipo de produto informado não existe no catálogo.',
            'pedido.*.sabor.exists'                     => 'O sabor selecionado não existe.',
            'pedido.*.tamanho.exists'                   => 'O tamanho selecionado não existe.',
            'pedido.*.quantidade.min'                   => 'A quantidade informada deve ser pelo menos 1.',

            // Entrega / Retirada
            'entrega_retirada.data.required'            => 'A data de entrega ou retirada é obrigatória.',
            'entrega_retirada.periodo.required'         => 'O período (Manhã/Tarde/Noite) é obrigatório.',

            // Retirada / Uber / Entrega
            'entrega_retirada.bairro_id.exists'         => 'O bairro informado não foi encontrado.',
            'entrega_retirada.numero'                   => 'O número do endereço deve ser informado.',
            'entrega_retirada.valor_uber'               => 'O valor do Uber deve ser informado.',
            'entrega_retirada.entregador_id'            => 'O entregador deve ser informado.',
        ];
    }
}
