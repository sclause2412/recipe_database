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
        Schema::create('translations', function (Blueprint $table) {
            $table->collation = 'utf8mb4_unicode_ci';
            $table->ulid('id')->primary();
            $table->string('locale');
            $table->string('group');
            $table->text('key')->collation('utf8mb4_bin');
            $table->text('value')->nullable();
            $table->boolean('done')->default(false);
            $table->extendedtimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
