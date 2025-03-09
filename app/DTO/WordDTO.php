<?php

namespace App\DTO;

use App\Models\Translation;
use App\Models\Word;
use Illuminate\Support\Arr;

class WordDTO
{
    private $data = [];
    private $class = Word::class;

    public function __construct(array $requestData)
    {
        $this->data = $requestData;
        $this->data['model']['show_at'] = now()->addHours($requestData['button'] ?? 0);
        if (Arr::get($requestData, 'reverse')) {
            $this->class = Translation::class;
        }
    }

    public function getModel(): Word|Translation
    {
        return $this->class::firstOrNew(['id' => Arr::get($this->data['model'], 'id')]);
    }

    public function isPartialUpdate(): bool
    {
        return Arr::get($this->data, 'button') !== null;
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function toFillableArray(): array
    {
        return $this->isPartialUpdate()
            ? Arr::only($this->data['model'], ['show_at'])
            : Arr::only($this->data['model'], (new Word())->getFillable());
    }

    public function toPartialArray(): array
    {
        return Arr::only($this->data['model'], ['show_at']);
    }
}