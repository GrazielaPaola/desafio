<?php

namespace App\Http\Requests;

use App\Rules\Cnpj;
use App\Rules\Placa;
use Illuminate\Foundation\Http\FormRequest;

class ColetaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data' => ['required', 'date_format:Y-m-d', 'after:today'],
            'fornecedor_nome' => ['required', 'string', 'max:255'],
            'fornecedor_cnpj' => ['required', new Cnpj],
            'cliente_nome' => ['required', 'string', 'max:255'],
            'cliente_cnpj' => ['required', new Cnpj],
            'motorista_id' => ['required', 'integer', 'exists:motoristas,id'],
            'placa_veiculo' => ['required', new Placa],
        ];
    }
}
