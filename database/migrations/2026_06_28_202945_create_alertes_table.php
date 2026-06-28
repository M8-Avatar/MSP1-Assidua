<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alertes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assiduite_id')->constrained('assiduites')->onDelete('cascade');
            $table->timestamp('date_alerte')->useCurrent();
            $table->boolean('vue_admin')->default(false);
            $table->boolean('vue_apprenant')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alertes');
    }
};