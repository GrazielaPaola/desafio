<?php

namespace App\Services;

use App\Exceptions\RegraDeNegocioException;
use App\Models\Motorista;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class MotoristaService
{
    private const COLUNAS_ORDENACAO = [
        'nome' => 'nome',
        'total_coletas' => 'coletas_count',
    ];

    private const ORDENACAO_PADRAO = 'nome';

    private const DIRECAO_PADRAO = 'asc';

    public function listar(array $filtros, int $porPagina): LengthAwarePaginator
    {
        $coluna = self::COLUNAS_ORDENACAO[$filtros['ordenar_por'] ?? self::ORDENACAO_PADRAO];

        return Motorista::query()
            ->withCount('coletas')
            ->when($filtros['busca'] ?? null, fn (Builder $consulta, string $busca) => $consulta->busca($busca))
            ->orderBy($coluna, $filtros['direcao'] ?? self::DIRECAO_PADRAO)
            ->orderBy('nome')
            ->paginate($porPagina);
    }

    public function criar(array $dados): Motorista
    {
        return Motorista::create($dados)->loadCount('coletas');
    }

    public function atualizar(Motorista $motorista, array $dados): Motorista
    {
        $motorista->update($dados);

        return $motorista->loadCount('coletas');
    }

    public function excluir(Motorista $motorista): void
    {
        if ($motorista->coletas()->exists()) {
            throw new RegraDeNegocioException('O motorista possui coletas agendadas e não pode ser excluído.');
        }

        $motorista->delete();
    }
}
