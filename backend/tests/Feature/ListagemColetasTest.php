<?php

namespace Tests\Feature;

use App\Models\Coleta;
use App\Models\Motorista;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListagemColetasTest extends TestCase
{
    use RefreshDatabase;

    public function test_filtra_por_nome_de_fornecedor_ou_cliente(): void
    {
        Coleta::factory()->create(['fornecedor_nome' => 'Alfa Ltda', 'cliente_nome' => 'Cliente Um']);
        Coleta::factory()->create(['fornecedor_nome' => 'Beta S.A.', 'cliente_nome' => 'Alfa Distribuidora']);
        Coleta::factory()->create(['fornecedor_nome' => 'Gama', 'cliente_nome' => 'Delta']);

        $this->getJson('/api/coletas?busca=Alfa')
            ->assertOk()
            ->assertJsonPath('meta.total', 2);
    }

    public function test_filtra_por_cnpj_com_ou_sem_mascara(): void
    {
        Coleta::factory()->create(['fornecedor_cnpj' => '11222333000181']);
        Coleta::factory()->create(['cliente_cnpj' => '12345678000195']);

        $this->getJson('/api/coletas?busca=11.222.333')->assertOk()->assertJsonPath('meta.total', 1);
        $this->getJson('/api/coletas?busca=12345678')->assertOk()->assertJsonPath('meta.total', 1);
    }

    public function test_filtra_por_motorista_e_intervalo_de_datas(): void
    {
        $motorista = Motorista::factory()->create();
        Coleta::factory()->for($motorista)->create(['data' => now()->addDays(2)->toDateString()]);
        Coleta::factory()->for($motorista)->create(['data' => now()->addDays(10)->toDateString()]);
        Coleta::factory()->create(['data' => now()->addDays(2)->toDateString()]);

        $this->getJson("/api/coletas?motorista_id={$motorista->id}")
            ->assertOk()
            ->assertJsonPath('meta.total', 2);

        $inicio = now()->addDay()->toDateString();
        $fim = now()->addDays(5)->toDateString();

        $this->getJson("/api/coletas?motorista_id={$motorista->id}&data_inicio={$inicio}&data_fim={$fim}")
            ->assertOk()
            ->assertJsonPath('meta.total', 1);
    }

    public function test_rejeita_data_final_anterior_a_inicial(): void
    {
        $this->getJson('/api/coletas?data_inicio=2026-09-10&data_fim=2026-09-01')
            ->assertUnprocessable()
            ->assertJsonPath('errors.data_fim.0', 'O campo data final deve ser uma data igual ou posterior a data inicial.');
    }

    public function test_ordena_por_coluna_e_direcao(): void
    {
        Coleta::factory()->create(['fornecedor_nome' => 'Bravo']);
        Coleta::factory()->create(['fornecedor_nome' => 'Alfa']);
        Coleta::factory()->create(['fornecedor_nome' => 'Charlie']);

        $this->getJson('/api/coletas?ordenar_por=fornecedor_nome&direcao=desc')
            ->assertOk()
            ->assertJsonPath('data.0.fornecedor_nome', 'Charlie')
            ->assertJsonPath('data.2.fornecedor_nome', 'Alfa');
    }

    public function test_ordena_pelo_nome_do_motorista(): void
    {
        Coleta::factory()->for(Motorista::factory()->create(['nome' => 'Zeca']))->create();
        Coleta::factory()->for(Motorista::factory()->create(['nome' => 'Ana']))->create();

        $this->getJson('/api/coletas?ordenar_por=motorista&direcao=asc')
            ->assertOk()
            ->assertJsonPath('data.0.motorista.nome', 'Ana');
    }

    public function test_rejeita_ordenacao_desconhecida(): void
    {
        $this->getJson('/api/coletas?ordenar_por=senha')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('ordenar_por');
    }

    public function test_exporta_csv_respeitando_filtros(): void
    {
        $motorista = Motorista::factory()->create(['nome' => 'Carlos']);
        Coleta::factory()->for($motorista)->create([
            'data' => now()->addDays(3)->toDateString(),
            'fornecedor_nome' => 'Fornecedor Exportado',
            'fornecedor_cnpj' => '11222333000181',
            'placa_veiculo' => 'ABC1234',
        ]);
        Coleta::factory()->create(['fornecedor_nome' => 'Outro Fornecedor']);

        $resposta = $this->get("/api/coletas/exportar?motorista_id={$motorista->id}");

        $resposta->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->assertDownload('coletas.csv');

        $conteudo = $resposta->streamedContent();

        $this->assertStringContainsString('Data;Fornecedor;"CNPJ do fornecedor";Cliente', $conteudo);
        $this->assertStringContainsString(now()->addDays(3)->format('d/m/Y').';"Fornecedor Exportado";11.222.333/0001-81', $conteudo);
        $this->assertStringContainsString('Carlos;ABC-1234', $conteudo);
        $this->assertStringNotContainsString('Outro Fornecedor', $conteudo);
    }
}
