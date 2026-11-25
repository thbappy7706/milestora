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
        Schema::create('roadmaps', function (Blueprint $table) {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('kind', 20)->default('learning');
            $table->string('status', 20)->default('draft');
            $table->date('start_date')->nullable();
            $table->date('target_end_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->unsignedInteger('daily_goal_minutes')->default(120);
            $table->json('active_weekdays')->nullable();
            $table->string('timezone', 64)->default('Asia/Dhaka');
            $table->decimal('progress_cached', 5, 2)->default(0);
            $table->unsignedInteger('total_estimate_minutes')->default(0);
            $table->unsignedInteger('total_logged_minutes')->default(0);
            $table->json('scoring_config')->nullable();
            $table->unsignedBigInteger('source_import_id')->nullable()->index();
            $table->boolean('is_template')->default(false);
            $table->boolean('is_public')->default(false);
            $table->string('share_token', 64)->nullable()->unique();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'slug']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roadmaps');
    }
};
