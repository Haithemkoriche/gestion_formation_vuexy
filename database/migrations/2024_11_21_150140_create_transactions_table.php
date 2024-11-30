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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caisse_id')->constrained('caisses')->onDelete('cascade'); // Link to a caisse
            $table->foreignId('service_entry_id')->constrained('service_entries')->onDelete('cascade'); // Link to service entry
            $table->decimal('amount', 10, 2); // Transaction amount
            $table->enum('type', ['entry', 'exit'])->default('entry'); // Type of transaction (entry or exit)
            $table->decimal('balance_before', 10, 2); // Balance before transaction
            $table->decimal('balance_after', 10, 2); // Balance after transaction
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
