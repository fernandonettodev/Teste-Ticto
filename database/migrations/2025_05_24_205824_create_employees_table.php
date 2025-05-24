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
        Schema::create('employees', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('document')->unique(); // CPF
            $table->string('position');
            $table->boolean('active')->default(true)->index();
            $table->date('birthdate');
            $table->string('zipcode');
            $table->string('address');
            $table->string('password');
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');

            $table->index(['admin_id', 'email', 'document', 'active']);


        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
