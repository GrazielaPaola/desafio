<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class ListarColetasRequest extends PaginacaoRequest
{
    public const ORDENACOES = ['data', 'fornecedor_nome', 'cliente_nome', 'placa_veiculo', 'motorista'];

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'busca' => ['sometimes', 'nullable', 'string', 'max:255'],
            'motorista_id' => ['sometimes', 'nullable', 'integer', 'exists:motoristas,id'],
            'data_inicio' => ['sometimes', 'nullable', 'date_format:Y-m-d'],
            'data_fim' => ['sometimes', 'nullable', 'date_format:Y-m-d', 'after_or_equal:data_inicio'],
            'ordenar_por' => ['sometimes', 'nullable', Rule::in(self::ORDENACOES)],
            'direcao' => ['sometimes', 'nullable', Rule::in(self::DIRECOES)],
        ]);
    }
}
