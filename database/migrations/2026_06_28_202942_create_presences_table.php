<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inscription_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->string('statut', 20);
            $table->string('observation', 255)->nullable();
            $table->timestamps();
            $table->unique(['inscription_id', 'date']);
        });

        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE presences ADD CONSTRAINT check_statut
                CHECK (statut IN ('present', 'absent', 'retard', 'absent_justifie'))");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};