<?php

namespace App\Services;

use App\Exceptions\RegraDeNegocioException;
use App\Models\Motorista;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MotoristaService
{
    public function listar(int $porPagina): LengthAwarePaginator
    {
        return Motorista::query()
            ->withCount('coletas')
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
