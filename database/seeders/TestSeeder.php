<?php

namespace Database\Seeders;

use App\Models\Dictionary;
use App\Models\Translation;
use App\Models\User;
use App\Models\Word;
use Database\Factories\DictionaryFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create();
        $dictionary = Dictionary::factory()
            ->has(
                Word::factory()->count(10)->has(Translation::factory()->count(Arr::random([1,2,3])), 'translations'),
                'words',
            )
            ->for($user)
            ->create();
    }
}
