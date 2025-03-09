<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('slug', 255);
            $table->timestamps();
        });

        DB::table('languages')->insert([
            ['name' => 'Русский', 'slug' => 'russian'],
            ['name' => 'Английский', 'slug' => 'english'],
            ['name' => 'Итальянский', 'slug' => 'italian'],
            ['name' => 'Испанский', 'slug' => 'spanish'],
            ['name' => 'Немецкий', 'slug' => 'german'],
            ['name' => 'Польский', 'slug' => 'polish'],
            ['name' => 'Французский', 'slug' => 'french'],
            ['name' => 'Латышский', 'slug' => 'lettish'],
            ['name' => 'Татарский', 'slug' => 'tatar'],
            ['name' => 'Китайский', 'slug' => 'chinese'],
            ['name' => 'Японский', 'slug' => 'japanese'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
