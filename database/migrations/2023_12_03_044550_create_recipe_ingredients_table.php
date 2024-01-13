<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recipe_ingredients', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('recipe_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignUlid('ingredient_id')->constrained()->cascadeOnDelete();
            $table->string('group')->nullable();
            $table->float('amount')->nullable();
            $table->foreignUlid('unit_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('sort')->default(999);
            $table->string('reference');
            $table->extendedtimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_ingredients');
    }
};
