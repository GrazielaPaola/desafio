<?php

namespace Tests\Unit;

use App\Rules\Cnpj;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class CnpjRuleTest extends TestCase
{
    private function validar(mixed $valor): \Illuminate\Validation\Validator
    {
        return Validator::make(['cnpj' => $valor], ['cnpj' => [new Cnpj]]);
    }

    public function test_aceita_cnpj_com_mascara_e_digitos_validos(): void
    {
        $this->assertTrue($this->validar('11.222.333/0001-81')->passes());
        $this->assertTrue($this->validar('12.345.678/0001-95')->passes());
    }

    public function test_rejeita_cnpj_sem_mascara(): void
    {
        $validador = $this->validar('11222333000181');

        $this->assertTrue($validador->fails());
        $this->assertStringContainsString('formato 00.000.000/0000-00', $validador->errors()->first('cnpj'));
    }

    public function test_rejeita_cnpj_com_digitos_verificadores_invalidos(): void
    {
        $validador = $this->validar('11.222.333/0001-82');

        $this->assertTrue($validador->fails());
        $this->assertStringContainsString('não é um CNPJ válido', $validador->errors()->first('cnpj'));
    }

    public function test_rejeita_cnpj_com_todos_os_digitos_iguais(): void
    {
        $this->assertTrue($this->validar('11.111.111/1111-11')->fails());
    }

    public function test_rejeita_valor_que_nao_e_texto(): void
    {
        $this->assertTrue($this->validar(11222333000181)->fails());
    }
}
