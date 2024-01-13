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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('user_id')->nullable()->index()->constrained()->cascadeOnDelete();
            $table->foreignUlid('project_id')->nullable()->index()->constrained()->cascadeOnDelete();
            $table->string('setting', 100);
            $table->text('data')->nullable();
            $table->extendedtimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
