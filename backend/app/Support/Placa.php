<?php

namespace App\Support;

final class Placa
{
    private const FORMATO_ANTIGO = '/^[A-Z]{3}\d{4}$/';

    private const FORMATO_MERCOSUL = '/^[A-Z]{3}\d[A-Z]\d{2}$/';

    private const TAMANHO_PREFIXO = 3;

    public static function normalizar(?string $valor): string
    {
        return strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', (string) $valor));
    }

    public static function formatar(?string $valor): string
    {
        $placa = self::normalizar($valor);

        if (preg_match(self::FORMATO_ANTIGO, $placa) !== 1) {
            return $placa;
        }

        return substr($placa, 0, self::TAMANHO_PREFIXO).'-'.substr($placa, self::TAMANHO_PREFIXO);
    }

    public static function ehValida(?string $valor): bool
    {
        $placa = self::normalizar($valor);

        return preg_match(self::FORMATO_ANTIGO, $placa) === 1
            || preg_match(self::FORMATO_MERCOSUL, $placa) === 1;
    }
}
