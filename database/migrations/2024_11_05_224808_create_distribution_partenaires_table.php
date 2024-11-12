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
        Schema::create('distribution_partenaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits');
            $table->foreignId('partenaire_id')->constrained('partenaires');
            $table->foreignId('user_id')->constrained('users');  // Chargé de distribution
            $table->decimal('quantity', 30, 2);  // Quantité distribuée
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribution_partenaires');
    }
};
