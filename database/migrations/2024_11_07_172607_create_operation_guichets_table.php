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
        Schema::create('operation_guichets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade'); // Référence au produit
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Guichetier
            $table->foreignId('site_id')->constrained('sites')->onDelete('cascade'); // Guichet où l'opération a eu lieu
            $table->enum('shift', ['Jour', 'Soir']); // Shift du guichetier
            $table->decimal('quantity_trouvee', 30, 2)->default(0); // Quantité trouvée dans le stock
            $table->decimal('quantity_recue', 30, 2)->default(0); // Quantité reçue du distributeur
            $table->decimal('quantity_restante', 30, 2)->default(0); // Quantité restante dans le stock
            $table->decimal('quantity_abimee', 30, 2)->default(0); // Quantité abîmée
            $table->decimal('quantity_consomme', 30, 2)->default(0); // Quantité consommée
            $table->decimal('quantity_dette', 30, 2)->default(0); // Quantité partie en dette
            $table->decimal('quantity_vente', 30, 2); // Quantité vendue (calculée)
            $table->decimal('prix', 30, 2); // Prix du produit
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_guichets');
    }
};
