<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->string('type'); // multiple-choice, code, etc.
            $table->text('question_text');
            $table->text('correct_answer');
            $table->text('explanation')->nullable();
            $table->integer('difficulty')->default(1);
            $table->json('options')->nullable(); // For multiple choice questions
            $table->integer('points')->default(1);
            $table->unsignedBigInteger('lesson_id');
            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
};
