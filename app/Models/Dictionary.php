<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Dictionary extends Model
{
    protected $table = 'dictionaries';

    protected $fillable = [
        'name',
        'language_1_id',
        'language_2_id',
        'user_id',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'date',
        'updated_at' => 'date',
    ];

    public function words(): HasMany
    {
        return $this->hasMany(Word::class, 'dictionary_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function language_from(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_1_id', 'id');
    }

    public function language_to(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_2_id', 'id');
    }

    public function getWordsCatalogAttribute(): Collection
    {
        return $this->words->groupBy('letter');
    }
}
