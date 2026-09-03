<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaginacaoRequest extends FormRequest
{
    public const POR_PAGINA_PADRAO = 15;

    public const POR_PAGINA_MAXIMO = 100;

    public const DIRECOES = ['asc', 'desc'];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'por_pagina' => ['sometimes', 'integer', 'min:1', 'max:'.self::POR_PAGINA_MAXIMO],
        ];
    }

    public function porPagina(): int
    {
        return $this->integer('por_pagina', self::POR_PAGINA_PADRAO);
    }

    public function filtros(): array
    {
        return $this->safe()->except(['por_pagina']);
    }
}
