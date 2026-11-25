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
        Schema::create('nodes', function (Blueprint $table) {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('roadmap_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('nodes')->nullOnDelete();
            $table->foreignId('node_type_id')->constrained('node_types')->restrictOnDelete();
            $table->string('path', 500)->default('');
            $table->unsignedInteger('depth')->default(0);
            $table->unsignedInteger('position')->default(0);
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('tracking_mode', 20)->default('checkbox');
            $table->string('status', 20)->default('todo');
            $table->decimal('target_value', 12, 2)->nullable();
            $table->decimal('current_value', 12, 2)->nullable();
            $table->string('unit', 20)->nullable();
            $table->unsignedInteger('estimate_minutes')->nullable();
            $table->unsignedTinyInteger('difficulty')->default(3);
            $table->decimal('weight', 8, 2)->default(1);
            $table->date('due_date')->nullable();
            $table->date('start_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->decimal('progress_cached', 5, 2)->default(0);
            $table->unsignedInteger('logged_minutes_cached')->default(0);
            $table->unsignedTinyInteger('score_self')->nullable();
            $table->string('evidence_url')->nullable();
            $table->string('import_key', 40)->nullable();
            $table->unsignedTinyInteger('confidence')->nullable();
            $table->json('recurrence')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['roadmap_id', 'parent_id', 'position']);
            $table->index(['roadmap_id', 'path']);
            $table->index(['roadmap_id', 'status']);
            $table->index(['roadmap_id', 'due_date']);
            $table->unique(['roadmap_id', 'import_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nodes');
    }
};
