<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Hapus kolom lama
            if (Schema::hasColumn('sales', 'customer_name')) {
                $table->dropColumn('customer_name');
            }

            // Tambahkan relasi ke customers
            $table->foreignId('customer_id')->nullable()->after('sale_date')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
            $table->string('customer_name')->nullable();
        });
    }
};
