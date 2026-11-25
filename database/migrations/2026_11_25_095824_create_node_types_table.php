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
        Schema::create('node_types', function (Blueprint $table) {
            $table->id();
            // null user_id = system-provided type, available to every account.
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('key', 50);
            $table->string('label');
            $table->string('icon', 50)->nullable();
            $table->string('color', 20)->nullable();
            $table->boolean('counts_toward_progress')->default(true);
            $table->string('default_tracking_mode', 20)->default('checkbox');
            $table->timestamps();

            $table->unique(['user_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('node_types');
    }
};
