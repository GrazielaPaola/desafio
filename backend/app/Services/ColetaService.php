<?php

namespace App\Services;

use App\Exceptions\RegraDeNegocioException;
use App\Models\Coleta;
use App\Support\Cnpj;
use App\Support\Placa;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ColetaService
{
    public function listar(int $porPagina): LengthAwarePaginator
    {
        return Coleta::query()
            ->with('motorista')
            ->orderBy('data')
            ->orderBy('id')
            ->paginate($porPagina);
    }

    public function criar(array $dados): Coleta
    {
        $this->garantirRegrasDeNegocio($dados);

        return Coleta::create($dados)->load('motorista');
    }

    public function atualizar(Coleta $coleta, array $dados): Coleta
    {
        $this->garantirRegrasDeNegocio($dados, $coleta);

        $coleta->update($dados);

        return $coleta->refresh()->load('motorista');
    }

    public function excluir(Coleta $coleta): void
    {
        $coleta->delete();
    }

    private function garantirRegrasDeNegocio(array $dados, ?Coleta $coletaAtual = null): void
    {
        $this->garantirFornecedorSemColetaNaData($dados, $coletaAtual);
        $this->garantirMotoristaComUmaUnicaPlaca($dados, $coletaAtual);
    }

    private function garantirFornecedorSemColetaNaData(array $dados, ?Coleta $coletaAtual): void
    {
        $existeColetaNaData = Coleta::query()
            ->where('fornecedor_cnpj', Cnpj::normalizar($dados['fornecedor_cnpj']))
            ->whereDate('data', $dados['data'])
            ->when($coletaAtual, fn ($query) => $query->whereKeyNot($coletaAtual->getKey()))
            ->exists();

        if ($existeColetaNaData) {
            throw new RegraDeNegocioException('O fornecedor já possui uma coleta agendada para esta data.');
        }
    }

    private function garantirMotoristaComUmaUnicaPlaca(array $dados, ?Coleta $coletaAtual): void
    {
        $coletaComOutraPlaca = Coleta::query()
            ->where('motorista_id', $dados['motorista_id'])
            ->where('placa_veiculo', '!=', Placa::normalizar($dados['placa_veiculo']))
            ->when($coletaAtual, fn ($query) => $query->whereKeyNot($coletaAtual->getKey()))
            ->first();

        if ($coletaComOutraPlaca === null) {
            return;
        }

        throw new RegraDeNegocioException(sprintf(
            'O motorista já está vinculado à placa %s e não pode receber outra placa.',
            Placa::formatar($coletaComOutraPlaca->placa_veiculo),
        ));
    }
}
