// database/migrations/xxxx_xx_xx_xxxxxx_create_user_exercise_submissions_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_exercise_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('exercise_id')->constrained()->onDelete('cascade');
            $table->text('submission_code');
            $table->string('status')->default('submitted'); // e.g., 'submitted', 'passed', 'failed'
            $table->integer('points_earned')->default(0);
            $table->text('feedback')->nullable();
            $table->timestamp('submitted_at')->useCurrent(); // Laravel will manage `created_at`, but this is for specific submission time
            $table->timestamps();

            // Optional: if you only want one "active" submission per user per exercise,
            // or if you want to enforce uniqueness on certain status.
            // $table->unique(['user_id', 'exercise_id', 'submitted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_exercise_submissions');
    }
};