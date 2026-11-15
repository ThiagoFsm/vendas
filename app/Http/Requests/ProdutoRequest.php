<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProdutoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('valor_produto') && is_string($this->valor_produto)) {
            $valorLimpo = str_replace(',', '.', str_replace('.', '', $this->valor_produto));
            $this->merge([
                'valor_produto' => is_numeric($valorLimpo) ? (float) $valorLimpo : $this->valor_produto,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'tipo_produto_id' => 'required|integer|exists:tipo_produtos,id',
            'sabor_id'        => 'required|integer|exists:sabores,id',
            'tamanho_id'      => 'required|integer|exists:tamanhos,id',
            'valor_produto'   => 'required|numeric|min:0',
            'ativo'           => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'tipo_produto_id.required' => 'O tipo de produto é obrigatório.',
            'tipo_produto_id.exists'   => 'O tipo de produto selecionado é inválido.',
            'sabor_id.required'        => 'O sabor é obrigatório.',
            'sabor_id.exists'          => 'O sabor selecionado é inválido.',
            'tamanho_id.required'      => 'O tamanho é obrigatório.',
            'tamanho_id.exists'        => 'O tamanho selecionado é inválido.',
            'valor_produto.required'   => 'O valor do produto é obrigatório.',
            'valor_produto.numeric'    => 'O valor do produto deve ser um número válido.',
            'valor_produto.min'        => 'O valor do produto não pode ser negativo.',
        ];
    }
}
