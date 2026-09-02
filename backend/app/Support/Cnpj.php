<?php

namespace App\Support;

final class Cnpj
{
    public const TAMANHO = 14;

    private const PESOS_PRIMEIRO_DIGITO = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

    private const PESOS_SEGUNDO_DIGITO = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

    private const POSICAO_PRIMEIRO_DIGITO = 12;

    private const POSICAO_SEGUNDO_DIGITO = 13;

    public static function normalizar(?string $valor): string
    {
        return preg_replace('/\D/', '', (string) $valor);
    }

    public static function formatar(?string $valor): string
    {
        $digitos = self::normalizar($valor);

        if (strlen($digitos) !== self::TAMANHO) {
            return $digitos;
        }

        return preg_replace('/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/', '$1.$2.$3/$4-$5', $digitos);
    }

    public static function possuiDigitosVerificadoresValidos(?string $valor): bool
    {
        $digitos = self::normalizar($valor);

        if (strlen($digitos) !== self::TAMANHO || self::todosDigitosIguais($digitos)) {
            return false;
        }

        $primeiroDigito = self::calcularDigitoVerificador($digitos, self::PESOS_PRIMEIRO_DIGITO);
        $segundoDigito = self::calcularDigitoVerificador($digitos, self::PESOS_SEGUNDO_DIGITO);

        return $primeiroDigito === (int) $digitos[self::POSICAO_PRIMEIRO_DIGITO]
            && $segundoDigito === (int) $digitos[self::POSICAO_SEGUNDO_DIGITO];
    }

    private static function calcularDigitoVerificador(string $digitos, array $pesos): int
    {
        $soma = 0;

        foreach ($pesos as $indice => $peso) {
            $soma += (int) $digitos[$indice] * $peso;
        }

        $resto = $soma % 11;

        return $resto < 2 ? 0 : 11 - $resto;
    }

    private static function todosDigitosIguais(string $digitos): bool
    {
        return preg_match('/^(\d)\1+$/', $digitos) === 1;
    }
}
