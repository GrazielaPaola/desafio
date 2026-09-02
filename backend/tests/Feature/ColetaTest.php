<?php

namespace Tests\Feature;

use App\Models\Coleta;
use App\Models\Motorista;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ColetaTest extends TestCase
{
    use RefreshDatabase;

    private const CNPJ_FORNECEDOR = '11.222.333/0001-81';

    private const CNPJ_CLIENTE = '12.345.678/0001-95';

    private function dadosValidos(array $sobrescrever = []): array
    {
        return array_merge([
            'data' => now()->addDay()->toDateString(),
            'fornecedor_nome' => 'Fornecedor Ltda',
            'fornecedor_cnpj' => self::CNPJ_FORNECEDOR,
            'cliente_nome' => 'Cliente S.A.',
            'cliente_cnpj' => self::CNPJ_CLIENTE,
            'motorista_id' => Motorista::factory()->create()->id,
            'placa_veiculo' => 'ABC-1234',
        ], $sobrescrever);
    }

    public function test_cria_coleta_e_normaliza_cnpj_e_placa(): void
    {
        $dados = $this->dadosValidos();

        $resposta = $this->postJson('/api/coletas', $dados);

        $resposta->assertCreated()
            ->assertJsonPath('fornecedor_cnpj', self::CNPJ_FORNECEDOR)
            ->assertJsonPath('placa_veiculo', 'ABC-1234')
            ->assertJsonPath('motorista.id', $dados['motorista_id']);

        $this->assertDatabaseHas('coletas', [
            'fornecedor_cnpj' => '11222333000181',
            'cliente_cnpj' => '12345678000195',
            'placa_veiculo' => 'ABC1234',
        ]);
    }

    public function test_regra_1_data_deve_ser_maior_que_a_data_atual(): void
    {
        $this->postJson('/api/coletas', $this->dadosValidos(['data' => now()->toDateString()]))
            ->assertUnprocessable()
            ->assertJsonPath('errors.data.0', 'A data do agendamento deve ser maior que a data atual.');

        $this->postJson('/api/coletas', $this->dadosValidos(['data' => now()->subDay()->toDateString()]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('data');
    }

    public function test_regra_2_fornecedor_tem_apenas_uma_coleta_por_data(): void
    {
        $dados = $this->dadosValidos();
        $this->postJson('/api/coletas', $dados)->assertCreated();

        $this->postJson('/api/coletas', $this->dadosValidos([
            'data' => $dados['data'],
            'placa_veiculo' => 'XYZ-9876',
        ]))
            ->assertConflict()
            ->assertJsonPath('message', 'O fornecedor já possui uma coleta agendada para esta data.');

        $this->postJson('/api/coletas', $this->dadosValidos([
            'data' => now()->addDays(2)->toDateString(),
            'placa_veiculo' => 'XYZ-9876',
        ]))->assertCreated();
    }

    public function test_regra_2_ignora_o_proprio_registro_na_edicao(): void
    {
        $dados = $this->dadosValidos();
        $id = $this->postJson('/api/coletas', $dados)->json('id');

        $this->putJson("/api/coletas/{$id}", array_merge($dados, ['fornecedor_nome' => 'Fornecedor Renomeado']))
            ->assertOk()
            ->assertJsonPath('fornecedor_nome', 'Fornecedor Renomeado');
    }

    public function test_regra_3_mesmo_cliente_pode_se_repetir(): void
    {
        $motorista = Motorista::factory()->create();

        $this->postJson('/api/coletas', $this->dadosValidos(['motorista_id' => $motorista->id]))->assertCreated();

        $this->postJson('/api/coletas', $this->dadosValidos([
            'fornecedor_cnpj' => '12.345.678/0001-95',
            'motorista_id' => $motorista->id,
        ]))->assertCreated();

        $this->assertSame(2, Coleta::query()->where('cliente_cnpj', '12345678000195')->count());
    }

    public function test_regra_4_motorista_e_obrigatorio_e_deve_existir(): void
    {
        $this->postJson('/api/coletas', $this->dadosValidos(['motorista_id' => null]))
            ->assertUnprocessable()
            ->assertJsonPath('errors.motorista_id.0', 'O campo motorista é obrigatório.');

        $this->postJson('/api/coletas', $this->dadosValidos(['motorista_id' => 999]))
            ->assertUnprocessable()
            ->assertJsonPath('errors.motorista_id.0', 'O motorista selecionado não existe.');
    }

    public function test_regra_5_placa_e_obrigatoria(): void
    {
        $this->postJson('/api/coletas', $this->dadosValidos(['placa_veiculo' => '']))
            ->assertUnprocessable()
            ->assertJsonPath('errors.placa_veiculo.0', 'O campo placa do veículo é obrigatório.');
    }

    public function test_regra_6_motorista_nao_pode_ter_duas_placas(): void
    {
        $motorista = Motorista::factory()->create();

        $this->postJson('/api/coletas', $this->dadosValidos([
            'motorista_id' => $motorista->id,
            'placa_veiculo' => 'ABC-1234',
        ]))->assertCreated();

        $this->postJson('/api/coletas', $this->dadosValidos([
            'fornecedor_cnpj' => '12.345.678/0001-95',
            'motorista_id' => $motorista->id,
            'placa_veiculo' => 'XYZ-9876',
        ]))
            ->assertConflict()
            ->assertJsonPath('message', 'O motorista já está vinculado à placa ABC-1234 e não pode receber outra placa.');

        $this->postJson('/api/coletas', $this->dadosValidos([
            'fornecedor_cnpj' => '12.345.678/0001-95',
            'motorista_id' => $motorista->id,
            'placa_veiculo' => 'abc1234',
        ]))->assertCreated();
    }

    public function test_regra_6_ignora_o_proprio_registro_na_edicao(): void
    {
        $dados = $this->dadosValidos(['placa_veiculo' => 'ABC-1234']);
        $id = $this->postJson('/api/coletas', $dados)->json('id');

        $this->putJson("/api/coletas/{$id}", array_merge($dados, ['placa_veiculo' => 'XYZ-9876']))
            ->assertOk()
            ->assertJsonPath('placa_veiculo', 'XYZ-9876');
    }

    public function test_regras_7_e_8_cnpj_de_fornecedor_e_cliente_validam_mascara_e_digitos(): void
    {
        $this->postJson('/api/coletas', $this->dadosValidos([
            'fornecedor_cnpj' => '11222333000181',
            'cliente_cnpj' => '12.345.678/0001-96',
        ]))
            ->assertUnprocessable()
            ->assertJsonPath('errors.fornecedor_cnpj.0', 'O campo CNPJ do fornecedor deve estar no formato 00.000.000/0000-00.')
            ->assertJsonPath('errors.cliente_cnpj.0', 'O campo CNPJ do cliente não é um CNPJ válido.');
    }

    public function test_lista_coletas_com_motorista_ordenadas_por_data(): void
    {
        $motorista = Motorista::factory()->create();
        Coleta::factory()->for($motorista)->create(['data' => now()->addDays(5)->toDateString()]);
        Coleta::factory()->for($motorista)->create(['data' => now()->addDays(2)->toDateString()]);

        $this->getJson('/api/coletas')
            ->assertOk()
            ->assertJsonPath('data.0.data', now()->addDays(2)->toDateString())
            ->assertJsonPath('data.0.motorista.nome', $motorista->nome)
            ->assertJsonPath('meta.total', 2);
    }

    public function test_exibe_coleta(): void
    {
        $coleta = Coleta::factory()->create();

        $this->getJson("/api/coletas/{$coleta->id}")
            ->assertOk()
            ->assertJsonPath('id', $coleta->id)
            ->assertJsonPath('motorista.id', $coleta->motorista_id);
    }

    public function test_exclui_coleta(): void
    {
        $coleta = Coleta::factory()->create();

        $this->deleteJson("/api/coletas/{$coleta->id}")->assertNoContent();

        $this->assertDatabaseMissing('coletas', ['id' => $coleta->id]);
    }

    public function test_retorna_404_para_coleta_inexistente(): void
    {
        $this->getJson('/api/coletas/999')
            ->assertNotFound()
            ->assertJsonPath('message', 'Registro não encontrado.');
    }
}
