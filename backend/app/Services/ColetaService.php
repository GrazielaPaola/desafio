<?php

namespace App\Services;

use App\Exceptions\RegraDeNegocioException;
use App\Models\Coleta;
use App\Support\Cnpj;
use App\Support\Placa;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\LazyCollection;

class ColetaService
{
    private const ORDENACAO_PADRAO = 'data';

    private const DIRECAO_PADRAO = 'asc';

    public function listar(array $filtros, int $porPagina): LengthAwarePaginator
    {
        return $this->consultaFiltrada($filtros)->paginate($porPagina);
    }

    public function listarParaExportacao(array $filtros): LazyCollection
    {
        return $this->consultaFiltrada($filtros)->lazy();
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

    private function consultaFiltrada(array $filtros): Builder
    {
        return Coleta::query()
            ->with('motorista')
            ->when($filtros['busca'] ?? null, fn (Builder $consulta, string $busca) => $consulta->busca($busca))
            ->when($filtros['motorista_id'] ?? null, fn (Builder $consulta, int $id) => $consulta->doMotorista($id))
            ->when($filtros['data_inicio'] ?? null, fn (Builder $consulta, string $data) => $consulta->aPartirDe($data))
            ->when($filtros['data_fim'] ?? null, fn (Builder $consulta, string $data) => $consulta->ate($data))
            ->ordenadoPor(
                $filtros['ordenar_por'] ?? self::ORDENACAO_PADRAO,
                $filtros['direcao'] ?? self::DIRECAO_PADRAO,
            );
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
            ->exceto($coletaAtual)
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
            ->exceto($coletaAtual)
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
