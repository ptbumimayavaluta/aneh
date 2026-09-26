<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('trx_code')->unique(); // Contoh: TRX-20260926-0001
            $table->string('customer_name');
            $table->enum('id_type', ['KTP', 'PASSPORT', 'SIM', 'KITAS', 'Lainnya']);
            $table->string('id_number')->nullable(); // Nomor ID/Identitas
            $table->string('country');
            $table->text('address')->nullable();
            $table->enum('type', ['BUY', 'SELL']); // BUY (Beli dari nasabah) / SELL (Jual ke nasabah)
            $table->foreignId('currency_id')->constrained('currencies')->onDelete('cascade');
            $table->decimal('amount', 15, 2);      // Jumlah Valas
            $table->decimal('rate', 15, 2);        // Kurs yang berlaku
            $table->decimal('total_idr', 15, 2);   // Total Rupiah
            $table->foreignId('user_id')->constrained('users'); // Kasir/Admin yang melayani
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};