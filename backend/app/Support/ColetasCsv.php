<?php

namespace App\Support;

use App\Models\Coleta;

final class ColetasCsv
{
    public const NOME_ARQUIVO = 'coletas.csv';

    public const CONTENT_TYPE = 'text/csv; charset=UTF-8';

    private const SEPARADOR = ';';

    private const BOM_UTF8 = "\xEF\xBB\xBF";

    private const FORMATO_DATA = 'd/m/Y';

    private const CABECALHO = [
        'Data',
        'Fornecedor',
        'CNPJ do fornecedor',
        'Cliente',
        'CNPJ do cliente',
        'Motorista',
        'Placa',
    ];

    public static function escrever(iterable $coletas): void
    {
        $saida = fopen('php://output', 'w');

        fwrite($saida, self::BOM_UTF8);
        fputcsv($saida, self::CABECALHO, self::SEPARADOR);

        foreach ($coletas as $coleta) {
            fputcsv($saida, self::linha($coleta), self::SEPARADOR);
        }

        fclose($saida);
    }

    private static function linha(Coleta $coleta): array
    {
        return [
            $coleta->data->format(self::FORMATO_DATA),
            $coleta->fornecedor_nome,
            Cnpj::formatar($coleta->fornecedor_cnpj),
            $coleta->cliente_nome,
            Cnpj::formatar($coleta->cliente_cnpj),
            $coleta->motorista->nome,
            Placa::formatar($coleta->placa_veiculo),
        ];
    }
}
