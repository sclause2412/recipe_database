<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignUlid('category_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('cooked')->default(false);
            $table->string('source')->nullable();
            $table->integer('portions')->nullable();
            $table->integer('time')->nullable();
            $table->text('description')->nullable();
            $table->boolean('active')->default(false);
            $table->extendedtimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
