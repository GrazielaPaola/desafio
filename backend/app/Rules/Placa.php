<?php

namespace App\Rules;

use App\Support\Placa as PlacaSupport;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Placa implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! PlacaSupport::ehValida($value)) {
            $fail('O campo :attribute deve estar no formato ABC-1234 ou ABC1D23.');
        }
    }
}
