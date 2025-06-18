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
    Schema::create('clientes', function (Blueprint $table) {
        $table->id();
        $table->string('nome');
        $table->string('cnpj')->unique();
        $table->string('email')->nullable();
        $table->string('telefone')->nullable();
        $table->string('status')->default('pendente');
        $table->timestamps();
    });
}
};
