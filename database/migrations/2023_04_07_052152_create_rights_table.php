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
        Schema::create('rights', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('user_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignUlid('project_id')->nullable()->index()->constrained()->cascadeOnDelete();
            $table->string('right', 100);
            $table->boolean('write')->default(false);
            $table->extendedtimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rights');
    }
};
