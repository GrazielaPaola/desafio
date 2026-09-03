<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class ListarMotoristasRequest extends PaginacaoRequest
{
    public const ORDENACOES = ['nome', 'total_coletas'];

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'busca' => ['sometimes', 'nullable', 'string', 'max:255'],
            'ordenar_por' => ['sometimes', 'nullable', Rule::in(self::ORDENACOES)],
            'direcao' => ['sometimes', 'nullable', Rule::in(self::DIRECOES)],
        ]);
    }
}
