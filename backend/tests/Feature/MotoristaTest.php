<?php

namespace Tests\Feature;

use App\Models\Coleta;
use App\Models\Motorista;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MotoristaTest extends TestCase
{
    use RefreshDatabase;

    public function test_lista_motoristas_paginados_em_ordem_alfabetica(): void
    {
        Motorista::factory()->create(['nome' => 'Zélia']);
        Motorista::factory()->create(['nome' => 'Ana']);

        $resposta = $this->getJson('/api/motoristas');

        $resposta->assertOk()
            ->assertJsonPath('data.0.nome', 'Ana')
            ->assertJsonPath('data.1.nome', 'Zélia')
            ->assertJsonPath('meta.total', 2);
    }

    public function test_cria_motorista(): void
    {
        $resposta = $this->postJson('/api/motoristas', ['nome' => 'Carlos Silva']);

        $resposta->assertCreated()
            ->assertJsonPath('nome', 'Carlos Silva')
            ->assertJsonPath('total_coletas', 0);

        $this->assertDatabaseHas('motoristas', ['nome' => 'Carlos Silva']);
    }

    public function test_nome_e_obrigatorio(): void
    {
        $resposta = $this->postJson('/api/motoristas', ['nome' => '']);

        $resposta->assertUnprocessable()
            ->assertJsonPath('errors.nome.0', 'O campo nome é obrigatório.');
    }

    public function test_exibe_motorista(): void
    {
        $motorista = Motorista::factory()->create();

        $this->getJson("/api/motoristas/{$motorista->id}")
            ->assertOk()
            ->assertJsonPath('id', $motorista->id);
    }

    public function test_atualiza_motorista(): void
    {
        $motorista = Motorista::factory()->create(['nome' => 'Antigo']);

        $this->putJson("/api/motoristas/{$motorista->id}", ['nome' => 'Novo'])
            ->assertOk()
            ->assertJsonPath('nome', 'Novo');

        $this->assertDatabaseHas('motoristas', ['id' => $motorista->id, 'nome' => 'Novo']);
    }

    public function test_exclui_motorista_sem_coletas(): void
    {
        $motorista = Motorista::factory()->create();

        $this->deleteJson("/api/motoristas/{$motorista->id}")->assertNoContent();

        $this->assertDatabaseMissing('motoristas', ['id' => $motorista->id]);
    }

    public function test_nao_exclui_motorista_com_coletas(): void
    {
        $coleta = Coleta::factory()->create();

        $this->deleteJson("/api/motoristas/{$coleta->motorista_id}")
            ->assertConflict()
            ->assertJsonPath('message', 'O motorista possui coletas agendadas e não pode ser excluído.');

        $this->assertDatabaseHas('motoristas', ['id' => $coleta->motorista_id]);
    }

    public function test_retorna_404_para_motorista_inexistente(): void
    {
        $this->getJson('/api/motoristas/999')
            ->assertNotFound()
            ->assertJsonPath('message', 'Registro não encontrado.');
    }
}
