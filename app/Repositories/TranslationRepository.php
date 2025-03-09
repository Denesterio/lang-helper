<?php

namespace App\Repositories;

use App\Models\Translation;
use App\Models\Word;

class TranslationRepository extends WordRepository
{
    protected string $class = Translation::class;
    protected string $translationClass = Word::class;
}
