<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Motorista extends Model
{
    use HasFactory;

    protected $fillable = ['nome'];

    public function coletas(): HasMany
    {
        return $this->hasMany(Coleta::class);
    }

    public function scopeBusca(Builder $query, string $termo): Builder
    {
        return $query->where('nome', 'like', "%{$termo}%");
    }

    public function scopeComResumo(Builder $query): Builder
    {
        return $query
            ->select('motoristas.*')
            ->withCount('coletas')
            ->addSelect([
                'placa_veiculo' => Coleta::query()
                    ->select('placa_veiculo')
                    ->whereColumn('motorista_id', 'motoristas.id')
                    ->limit(1),
                'proxima_coleta' => Coleta::query()
                    ->select('data')
                    ->whereColumn('motorista_id', 'motoristas.id')
                    ->aPartirDe(now()->toDateString())
                    ->orderBy('data')
                    ->limit(1),
            ]);
    }
}
