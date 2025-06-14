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
        Schema::table('user_progress', function (Blueprint $table) {
            // Drop old columns if they exist
            if (Schema::hasColumn('user_progress', 'lesson_id')) {
                // Drop foreign key before dropping the column
                $table->dropForeign(['lesson_id']);
                $table->dropColumn('lesson_id');
            }
            if (Schema::hasColumn('user_progress', 'completed')) {
                $table->dropColumn('completed');
            }
            if (Schema::hasColumn('user_progress', 'points_earned')) {
                $table->dropColumn('points_earned');
            }

            // Add new polymorphic columns and their associated fields
            if (!Schema::hasColumn('user_progress', 'progressable_id')) {
                $table->nullableMorphs('progressable');
            }
            // Ensure status, score, completed_at, and last_accessed_at are added if they don't exist
            if (!Schema::hasColumn('user_progress', 'status')) {
                $table->string('status')->default('started')->after('user_id');
            }
            if (!Schema::hasColumn('user_progress', 'score')) {
                $table->integer('score')->default(0)->after('status');
            }
            if (!Schema::hasColumn('user_progress', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('score');
            }
            if (!Schema::hasColumn('user_progress', 'last_accessed_at')) {
                $table->timestamp('last_accessed_at')->nullable()->after('completed_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_progress', function (Blueprint $table) {
            // Reverse the changes made in the 'up' method
            if (Schema::hasColumn('user_progress', 'progressable_id')) {
                $table->dropMorphs('progressable');
            }
            if (Schema::hasColumn('user_progress', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('user_progress', 'score')) {
                $table->dropColumn('score');
            }
            if (Schema::hasColumn('user_progress', 'completed_at')) {
                $table->dropColumn('completed_at');
            }
            if (Schema::hasColumn('user_progress', 'last_accessed_at')) {
                $table->dropColumn('last_accessed_at');
            }

            // Re-add old columns (optional, for full rollback)
            // Uncomment if you need to revert to the exact previous schema state
            // if (!Schema::hasColumn('user_progress', 'lesson_id')) {
            //     $table->foreignId('lesson_id')->nullable()->constrained()->onDelete('cascade');
            // }
            // if (!Schema::hasColumn('user_progress', 'completed')) {
            //     $table->boolean('completed')->default(false);
            // }
            // if (!Schema::hasColumn('user_progress', 'points_earned')) {
            //     $table->integer('points_earned')->default(0);
            // }
        });
    }
};
