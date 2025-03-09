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
        Schema::create('words', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dictionary_id');
            $table->string('word', 255);
            $table->string('letter', 10);
            $table->timestampTz('show_at')->nullable();
            $table->timestamps();

            $table->foreign('dictionary_id')->references('id')->on('dictionaries');
            $table->index('dictionary_id');
        });

        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dictionary_id');
            $table->string('word', 255);
            $table->string('letter', 10);
            $table->timestampTz('show_at')->nullable();
            $table->timestamps();

            $table->foreign('dictionary_id')->references('id')->on('dictionaries');
            $table->index('dictionary_id');
        });

        Schema::create('translation_word', function (Blueprint $table) {
            $table->unsignedBigInteger('word_id');
            $table->unsignedBigInteger('translation_id');

            $table->foreign('word_id')->references('id')->on('words')->onDelete('cascade');
            $table->foreign('translation_id')->references('id')->on('translations')->onDelete('cascade');

            $table->index(['word_id', 'dictionary_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('translation_word', function (Blueprint $table) {
            $table->dropIndex(['word_id', 'dictionary_id']);
        });
        Schema::table('translations', function (Blueprint $table) {
            $table->dropIndex(['dictionary_id']);
        });
        Schema::table('words', function (Blueprint $table) {
            $table->dropIndex(['dictionary_id']);
        });
        Schema::dropIfExists('translation_word');
        Schema::dropIfExists('translations');
        Schema::dropIfExists('words');
    }
};
