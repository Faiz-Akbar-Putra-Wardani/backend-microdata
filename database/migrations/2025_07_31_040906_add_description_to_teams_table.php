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
        Schema::table('teams', function (Blueprint $table) {
            // Menambahkan kolom description bertipe text, wajib diisi, dengan default value
            $table->text('description')->default('Deskripsi belum diisi');
        });
    }

    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            // Jika rollback, hapus kolom description
            $table->dropColumn('description');
        });
    }
};
