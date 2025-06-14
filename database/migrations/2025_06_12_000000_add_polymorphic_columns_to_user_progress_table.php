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
            $table->nullableMorphs('progressable');
            $table->string('status')->default('started')->after('level_id');
            $table->integer('score')->default(0)->after('status');
            $table->timestamp('completed_at')->nullable()->after('score');
            $table->timestamp('last_accessed_at')->nullable()->after('completed_at');

            // Remove old columns if they exist and are no longer needed
            // $table->dropColumn(['completed', 'score_old', 'completed_at_old']);
            // You might need to adjust column names based on your previous schema if a rename was intended.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_progress', function (Blueprint $table) {
            $table->dropMorphs('progressable');
            $table->dropColumn(['status', 'score', 'completed_at', 'last_accessed_at']);

            // Re-add old columns if you dropped them in the 'up' method
            // $table->boolean('completed')->default(false);
            // $table->integer('score_old')->default(0);
            // $table->timestamp('completed_at_old')->nullable();
        });
    }
};
