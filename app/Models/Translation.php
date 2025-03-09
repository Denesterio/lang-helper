<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Translation extends Word
{
    protected $table = 'translations';

    public function translations(): BelongsToMany
    {
        return $this->belongsToMany(Word::class, 'translation_word', 'translation_id', 'word_id');
    }
}
