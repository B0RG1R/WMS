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
        Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('sku')->unique(); // kode produk unik
        $table->string('category')->nullable(); // nanti bisa diubah ke relasi
        $table->integer('stock')->default(0);
        $table->decimal('price', 12, 2);
        $table->string('unit')->default('pcs');
        $table->text('description')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
