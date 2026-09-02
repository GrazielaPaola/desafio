<?php

namespace App\Models;

use App\Support\Cnpj;
use App\Support\Placa;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coleta extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'fornecedor_nome',
        'fornecedor_cnpj',
        'cliente_nome',
        'cliente_cnpj',
        'motorista_id',
        'placa_veiculo',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'date:Y-m-d',
        ];
    }

    public function motorista(): BelongsTo
    {
        return $this->belongsTo(Motorista::class);
    }

    protected function fornecedorCnpj(): Attribute
    {
        return Attribute::make(set: fn (?string $valor) => Cnpj::normalizar($valor));
    }

    protected function clienteCnpj(): Attribute
    {
        return Attribute::make(set: fn (?string $valor) => Cnpj::normalizar($valor));
    }

    protected function placaVeiculo(): Attribute
    {
        return Attribute::make(set: fn (?string $valor) => Placa::normalizar($valor));
    }
}
