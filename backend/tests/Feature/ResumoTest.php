<?php

namespace Tests\Feature;

use App\Models\Coleta;
use App\Models\Motorista;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResumoTest extends TestCase
{
    use RefreshDatabase;

    public function test_retorna_indicadores_e_proximas_coletas(): void
    {
        $motoristaAtivo = Motorista::factory()->create();
        Motorista::factory()->create();

        Coleta::factory()->for($motoristaAtivo)->create(['data' => now()->toDateString(), 'placa_veiculo' => 'ABC1234']);
        Coleta::factory()->for($motoristaAtivo)->create(['data' => now()->addDays(3)->toDateString(), 'placa_veiculo' => 'ABC1234']);
        Coleta::factory()->for($motoristaAtivo)->create(['data' => now()->addDays(20)->toDateString(), 'placa_veiculo' => 'ABC1234']);
        Coleta::factory()->for($motoristaAtivo)->create(['data' => now()->subDays(2)->toDateString(), 'placa_veiculo' => 'ABC1234']);

        $this->getJson('/api/resumo')
            ->assertOk()
            ->assertJsonPath('dias_proximos', 7)
            ->assertJsonPath('coletas_hoje', 1)
            ->assertJsonPath('coletas_proximos_dias', 1)
            ->assertJsonPath('coletas_futuras', 3)
            ->assertJsonPath('total_motoristas', 2)
            ->assertJsonPath('motoristas_com_coletas', 1)
            ->assertJsonCount(3, 'proximas_coletas')
            ->assertJsonPath('proximas_coletas.0.data', now()->toDateString())
            ->assertJsonPath('proximas_coletas.0.motorista.id', $motoristaAtivo->id);
    }
}
