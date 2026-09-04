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
        $motoristaAtivo = Motorista::factory()->create(['nome' => 'Ativo']);
        Motorista::factory()->create(['nome' => 'Sem coletas']);

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

    public function test_conta_apenas_as_coletas_da_semana_atual(): void
    {
        Coleta::factory()->create(['data' => now()->startOfWeek()->toDateString()]);
        Coleta::factory()->create(['data' => now()->endOfWeek()->toDateString()]);
        Coleta::factory()->create(['data' => now()->startOfWeek()->subDay()->toDateString()]);
        Coleta::factory()->create(['data' => now()->endOfWeek()->addDay()->toDateString()]);

        $this->getJson('/api/resumo')
            ->assertOk()
            ->assertJsonPath('coletas_semana', 2);
    }

    public function test_lista_carga_futura_por_motorista_em_ordem_decrescente(): void
    {
        $ocupado = Motorista::factory()->create(['nome' => 'Ocupado']);
        $tranquilo = Motorista::factory()->create(['nome' => 'Tranquilo']);
        $ocioso = Motorista::factory()->create(['nome' => 'Ocioso']);

        Coleta::factory()->count(2)->for($ocupado)->create(['placa_veiculo' => 'ABC1234']);
        Coleta::factory()->for($tranquilo)->create(['placa_veiculo' => 'XYZ9876']);
        Coleta::factory()->for($ocioso)->create([
            'data' => now()->subDays(3)->toDateString(),
            'placa_veiculo' => 'QWE4567',
        ]);

        $this->getJson('/api/resumo')
            ->assertOk()
            ->assertJsonCount(3, 'carga_motoristas')
            ->assertJsonPath('carga_motoristas.0.id', $ocupado->id)
            ->assertJsonPath('carga_motoristas.0.coletas_futuras', 2)
            ->assertJsonPath('carga_motoristas.1.id', $tranquilo->id)
            ->assertJsonPath('carga_motoristas.1.coletas_futuras', 1)
            ->assertJsonPath('carga_motoristas.2.id', $ocioso->id)
            ->assertJsonPath('carga_motoristas.2.coletas_futuras', 0);
    }
}
