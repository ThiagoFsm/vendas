<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaborRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('descricao') && is_string($this->descricao)) {
            $this->merge([
                'descricao' => trim($this->descricao),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'descricao' => 'required|string|max:100',
            'ativo'     => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'descricao.required' => 'A descrição do sabor é obrigatória.',
            'descricao.string'   => 'A descrição do sabor deve ser um texto válido.',
            'descricao.max'      => 'A descrição do sabor não pode ter mais de 100 caracteres.',
        ];
    }
}
