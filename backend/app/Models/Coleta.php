<?php

namespace App\Models;

use App\Support\Cnpj;
use App\Support\Placa;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coleta extends Model
{
    use HasFactory;

    private const ORDENACAO_POR_MOTORISTA = 'motorista';

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

    public function scopeBusca(Builder $query, string $termo): Builder
    {
        $digitos = Cnpj::normalizar($termo);

        return $query->where(function (Builder $consulta) use ($termo, $digitos) {
            $consulta
                ->where('fornecedor_nome', 'like', "%{$termo}%")
                ->orWhere('cliente_nome', 'like', "%{$termo}%");

            if ($digitos !== '') {
                $consulta
                    ->orWhere('fornecedor_cnpj', 'like', "%{$digitos}%")
                    ->orWhere('cliente_cnpj', 'like', "%{$digitos}%");
            }
        });
    }

    public function scopeDoMotorista(Builder $query, int $motoristaId): Builder
    {
        return $query->where('motorista_id', $motoristaId);
    }

    public function scopeAPartirDe(Builder $query, string $data): Builder
    {
        return $query->whereDate('data', '>=', $data);
    }

    public function scopeAte(Builder $query, string $data): Builder
    {
        return $query->whereDate('data', '<=', $data);
    }

    public function scopeOrdenadoPor(Builder $query, string $coluna, string $direcao): Builder
    {
        if ($coluna === self::ORDENACAO_POR_MOTORISTA) {
            $nomeDoMotorista = Motorista::query()
                ->select('nome')
                ->whereColumn('motoristas.id', 'coletas.motorista_id');

            return $query->orderBy($nomeDoMotorista, $direcao)->orderBy('data');
        }

        return $query->orderBy($coluna, $direcao)->orderBy('id');
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
