<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Word extends Model
{
    protected $table = 'words';

    protected $fillable = [
        'word',
        'letter',
        'dictionary_id',
        'show_at',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'date',
        'updated_at' => 'date',
        'show_at' => 'date',
    ];

    public function dictionary(): BelongsTo
    {
        return $this->belongsTo(Dictionary::class, 'dictionary_id');
    }

    public function translations(): BelongsToMany
    {
        return $this->belongsToMany(Translation::class, 'translation_word', 'word_id', 'translation_id');
    }

    public function getTranslationTextAttribute(): ?string
    {
        return $this->translations->pluck('word')->join('. ');
    }
}
