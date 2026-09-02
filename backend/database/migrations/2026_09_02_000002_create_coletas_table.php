<?php

use App\Support\Cnpj;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TAMANHO_PLACA = 7;

    public function up(): void
    {
        Schema::create('coletas', function (Blueprint $table) {
            $table->id();
            $table->date('data');
            $table->string('fornecedor_nome');
            $table->string('fornecedor_cnpj', Cnpj::TAMANHO);
            $table->string('cliente_nome');
            $table->string('cliente_cnpj', Cnpj::TAMANHO);
            $table->foreignId('motorista_id')->constrained('motoristas')->restrictOnDelete();
            $table->string('placa_veiculo', self::TAMANHO_PLACA);
            $table->timestamps();

            $table->unique(['fornecedor_cnpj', 'data']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coletas');
    }
};
