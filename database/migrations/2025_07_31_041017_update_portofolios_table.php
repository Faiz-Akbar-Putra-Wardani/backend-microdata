<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up(): void {
        Schema::table('portofolios', function (Blueprint $table) {
            $table->renameColumn('name', 'title');
            $table->renameColumn('description', 'name_project');
            $table->string('company_name');
            $table->string('image_portofolio');
        });
    }

    public function down(): void {
        Schema::table('portofolios', function (Blueprint $table) {
            $table->renameColumn('title', 'name');
            $table->renameColumn('name_project', 'description');
            $table->dropColumn(['company_name', 'image_portofolio']);
        });
    }
};
