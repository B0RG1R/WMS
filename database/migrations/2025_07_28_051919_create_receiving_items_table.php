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
        Schema::create('receiving_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receiving_id')->constrained('receivings')->onDelete('cascade');
            $table->string('product_name');
            $table->integer('received_qty');
            $table->string('condition')->nullable(); // optional: bagus/rusak
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receiving_items');
    }
};
