<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('time_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('clock_in')->nullable(); // Entrada
            $table->time('lunch_out')->nullable(); // Saída almoço
            $table->time('lunch_in')->nullable(); // Volta almoço
            $table->time('clock_out')->nullable(); // Saída
            $table->decimal('total_hours', 5, 2)->nullable(); // Total de horas
            $table->text('notes')->nullable(); // Observações
            $table->enum('status', ['complete', 'incomplete', 'pending'])->default('pending');
            $table->timestamps();
            
            $table->engine = 'InnoDB';
            $table->unique(['employee_id', 'date']); // Um registro por dia por funcionário
            $table->index(['date']);
            $table->index(['status']);
            $table->index(['employee_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_records');
    }
};
