<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5)->unique(); // USD, AUD, EUR, dll.
            $table->string('name');              // US Dollar, Euro, dll.
            $table->string('symbol', 10);        // $, €, ¥, dll.
            $table->decimal('buy_rate', 15, 2);  // Kurs Beli
            $table->decimal('sell_rate', 15, 2); // Kurs Jual
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};