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
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories');
            $table->string('nom');
            $table->decimal('prix', 12,2);
            $table->decimal('kg_par_sac', 12,2)->default(25);
            $table->decimal('qte_par_sac', 12,2)->default(0)->nullable();;
            $table->decimal('qte_par_kg', 12,2)->default(0)->nullable();
            $table->decimal('solde', 12,2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
