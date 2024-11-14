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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255); // Nom du service
            $table->text('description')->nullable(); // Description du service
            $table->boolean('status')->default(true); // Statut (actif/inactif)
            $table->decimal('price', 8, 2); // Prix avec deux décimales
            $table->json('required_documents')->nullable(); // Documents requis (tableau JSON)
            $table->timestamps(); // Champs created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
