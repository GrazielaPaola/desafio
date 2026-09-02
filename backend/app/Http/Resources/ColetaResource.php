<?php

namespace App\Http\Resources;

use App\Support\Cnpj;
use App\Support\Placa;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ColetaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'data' => $this->data->toDateString(),
            'fornecedor_nome' => $this->fornecedor_nome,
            'fornecedor_cnpj' => Cnpj::formatar($this->fornecedor_cnpj),
            'cliente_nome' => $this->cliente_nome,
            'cliente_cnpj' => Cnpj::formatar($this->cliente_cnpj),
            'motorista_id' => $this->motorista_id,
            'motorista' => MotoristaResource::make($this->whenLoaded('motorista')),
            'placa_veiculo' => Placa::formatar($this->placa_veiculo),
        ];
    }
}
