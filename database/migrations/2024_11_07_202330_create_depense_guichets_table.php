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
        Schema::create('depense_guichets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');  // Guichetier
            $table->foreignId('site_id')->constrained('sites')->onDelete('cascade');  // Site de guichet
            $table->enum('shift', ['Jour', 'Soir']);  // Shift
            $table->decimal('montant', 30, 2);  // Montant de la dépense
            $table->string('motif');  // Motif de la dépense
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depense_guichets');
    }
};
