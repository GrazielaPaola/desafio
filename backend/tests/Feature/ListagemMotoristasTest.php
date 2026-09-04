<?php

namespace Tests\Feature;

use App\Models\Coleta;
use App\Models\Motorista;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListagemMotoristasTest extends TestCase
{
    use RefreshDatabase;

    public function test_filtra_por_nome(): void
    {
        Motorista::factory()->create(['nome' => 'Carlos Silva']);
        Motorista::factory()->create(['nome' => 'Ana Carla']);
        Motorista::factory()->create(['nome' => 'Roberto']);

        $this->getJson('/api/motoristas?busca=carl')
            ->assertOk()
            ->assertJsonPath('meta.total', 2);
    }

    public function test_ordena_por_total_de_coletas(): void
    {
        $semColetas = Motorista::factory()->create(['nome' => 'Sem Coletas']);
        $comColetas = Motorista::factory()->create(['nome' => 'Com Coletas']);
        Coleta::factory()->count(2)->for($comColetas)->create(['placa_veiculo' => 'ABC1234']);

        $this->getJson('/api/motoristas?ordenar_por=total_coletas&direcao=desc')
            ->assertOk()
            ->assertJsonPath('data.0.id', $comColetas->id)
            ->assertJsonPath('data.0.total_coletas', 2)
            ->assertJsonPath('data.1.id', $semColetas->id);
    }

    public function test_rejeita_direcao_invalida(): void
    {
        $this->getJson('/api/motoristas?direcao=cima')
            ->assertUnprocessable()
            ->assertJsonPath('errors.direcao.0', 'O valor de direção é inválido.');
    }

    public function test_lista_traz_placa_vinculada_e_proxima_coleta(): void
    {
        $motorista = Motorista::factory()->create(['nome' => 'Com placa']);
        Coleta::factory()->for($motorista)->create([
            'data' => now()->addDays(4)->toDateString(),
            'placa_veiculo' => 'ABC1234',
        ]);
        Coleta::factory()->for($motorista)->create([
            'data' => now()->addDays(9)->toDateString(),
            'placa_veiculo' => 'ABC1234',
        ]);

        $this->getJson('/api/motoristas')
            ->assertOk()
            ->assertJsonPath('data.0.placa_veiculo', 'ABC-1234')
            ->assertJsonPath('data.0.proxima_coleta', now()->addDays(4)->toDateString());
    }

    public function test_motorista_sem_coletas_nao_tem_placa_nem_proxima_coleta(): void
    {
        Motorista::factory()->create(['nome' => 'Sem placa']);

        $this->getJson('/api/motoristas')
            ->assertOk()
            ->assertJsonPath('data.0.placa_veiculo', null)
            ->assertJsonPath('data.0.proxima_coleta', null);
    }
}
