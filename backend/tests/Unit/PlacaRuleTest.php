<?php

namespace Tests\Unit;

use App\Rules\Placa;
use App\Support\Placa as PlacaSupport;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class PlacaRuleTest extends TestCase
{
    private function validar(mixed $valor): \Illuminate\Validation\Validator
    {
        return Validator::make(['placa' => $valor], ['placa' => [new Placa]]);
    }

    public function test_aceita_formato_antigo_com_e_sem_hifen(): void
    {
        $this->assertTrue($this->validar('ABC-1234')->passes());
        $this->assertTrue($this->validar('ABC1234')->passes());
        $this->assertTrue($this->validar('abc-1234')->passes());
    }

    public function test_aceita_formato_mercosul(): void
    {
        $this->assertTrue($this->validar('ABC1D23')->passes());
    }

    public function test_rejeita_formatos_invalidos(): void
    {
        $this->assertTrue($this->validar('AB-1234')->fails());
        $this->assertTrue($this->validar('ABCD123')->fails());
        $this->assertTrue($this->validar('1234ABC')->fails());
    }

    public function test_normaliza_e_formata_placa(): void
    {
        $this->assertSame('ABC1234', PlacaSupport::normalizar('abc-1234'));
        $this->assertSame('ABC-1234', PlacaSupport::formatar('abc1234'));
        $this->assertSame('ABC1D23', PlacaSupport::formatar('abc1d23'));
    }
}
