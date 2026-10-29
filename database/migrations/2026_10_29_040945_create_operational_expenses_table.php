<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operational_expenses', function (Blueprint $table) {
            $table->id();
            $table->date('date'); // Tanggal pengeluaran biaya
            $table->decimal('amount', 15, 2); // Nominal biaya dalam IDR
            $table->text('description'); // Keterangan biaya operasional (misal: Beli ATK, Listrik, dll)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Kasir/Admin yang input
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operational_expenses');
    }
};