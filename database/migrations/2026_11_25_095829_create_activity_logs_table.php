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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('roadmap_id')->constrained()->cascadeOnDelete();
            $table->foreignId('node_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 20);
            $table->unsignedInteger('minutes')->nullable();
            $table->decimal('value', 12, 2)->nullable();
            $table->text('note')->nullable();
            $table->timestamp('logged_at');
            // User-timezone date; every daily statistic groups by this column.
            $table->date('local_date');
            $table->timestamps();

            $table->index(['roadmap_id', 'local_date']);
            $table->index(['user_id', 'local_date']);
            $table->index(['node_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
