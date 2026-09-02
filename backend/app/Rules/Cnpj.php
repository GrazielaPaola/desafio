<?php

namespace App\Rules;

use App\Support\Cnpj as CnpjSupport;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Cnpj implements ValidationRule
{
    private const MASCARA = '/^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/';

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || preg_match(self::MASCARA, $value) !== 1) {
            $fail('O campo :attribute deve estar no formato 00.000.000/0000-00.');

            return;
        }

        if (! CnpjSupport::possuiDigitosVerificadoresValidos($value)) {
            $fail('O campo :attribute não é um CNPJ válido.');
        }
    }
}
