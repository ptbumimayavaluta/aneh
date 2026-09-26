<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->softDeletes(); // Kolom deleted_at untuk data menggantung
            $table->foreignId('updated_by')->nullable()->constrained('users'); // Siapa yang edit
            $table->foreignId('deleted_by')->nullable()->constrained('users'); // Siapa yang hapus
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['deleted_by']);
            $table->dropColumn(['updated_by', 'deleted_by']);
        });
    }
};