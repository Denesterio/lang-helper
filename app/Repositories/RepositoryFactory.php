<?php

namespace App\Repositories;

use App\Models\Dictionary;
use App\Models\Language;
use App\Models\Translation;
use App\Models\User;
use App\Models\Word;

class RepositoryFactory
{
    public static function create(): RepositoryFactory
    {
        return new self;
    }

    public function getRepository(string $class)
    {
        switch ($class) {
            case User::class:
                return new UserRepository();
            case Dictionary::class:
                return new DictionaryRepository();
            case Word::class:
                return new WordRepository();
            case Translation::class:
                return new TranslationRepository();
            case Language::class:
                return new LanguageRepository();
            default:
                return null;
        }
    }
}
