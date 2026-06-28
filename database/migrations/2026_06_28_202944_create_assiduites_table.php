<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assiduites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inscription_id')->unique()->constrained()->onDelete('cascade');
            $table->decimal('taux', 5, 2)->default(0);
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assiduites');
    }
};