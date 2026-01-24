<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projetos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cliente_id')
                ->constrained('clientes')
                ->cascadeOnDelete();

            $table->string('nome');
            $table->text('descricao')->nullable();

            $table->date('data_inicio');
            $table->date('data_fim')->nullable();

            $table->decimal('valor_contrato', 12, 2);
            $table->decimal('custo_hora_base', 12, 2);

            $table->string('status', 20)->default('planejado'); 
            // planejado | em_andamento | pausado | finalizado

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projetos');
    }
};
