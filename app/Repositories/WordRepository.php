<?php

namespace App\Repositories;

use App\DTO\WordDTO;
use App\Models\Translation;
use App\Models\Word;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class WordRepository
{
    protected string  $class = Word::class;
    protected string  $translationClass = Translation::class;

    /**
     * Display a listing of the resource.
     */
    public function index(array $filters): Collection
    {
        $query = $this->class::with(['translations']);
        if (!empty(Arr::get($filters, 'id'))) {
            $query = $query->whereIn('id', $filters['id']);
        }
        if ((Arr::get($filters, 'dictionary_id'))) {
            $query = $query->where('dictionary_id', $filters['dictionary_id']);
        }

        return $query->with(['translations'])->get()->append(['translation_text']);
    }

    public function filterProcess(int $dictionaryId): Collection
    {
        return $this->class::whereDictionaryId($dictionaryId)
            ->where(function ($whereQuery) {
                $whereQuery->whereNull('show_at')
                    ->orWhere('show_at', '<', now());
            })
            ->has('translations')
            ->with(['translations'])
            ->get()
            ->append(['translation_text']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(array $data): Word|Translation|null
    {
        $word = $this->class::firstOrCreate(
            ['word' => $data['word'], 'dictionary_id' => $data['dictionary_id']],
            ['letter' => mb_strtolower(mb_substr($data['word'], 0, 1))]
        );
        $this->afterSave($data, $word);
        return $this->show($word->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): Word|Translation|null
    {
        return $this->class::with(['translations'])->find($id)->append(['translation_text']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WordDTO $dto, Word|Translation $word): Word|Translation|null
    {
        $word->fill($dto->toFillableArray());
        $word->save();
        if (!$dto->isPartialUpdate()) {
            $this->afterSave($dto->toArray(), $word);
        }
        return $this->show($word->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Word|Translation $word)
    {
        $word->translations()->delete();
        $word->delete();
    }

    protected function afterSave(array $data, Word|Translation $word): void
    {
        $word->translations()->delete();
        $dataTranslations = array_map('trim', explode('.', Arr::get($data, 'translation_text')));
        $newTranslations = [];
        foreach ($dataTranslations as $dataTranslation) {
            $translationModel = $this->translationClass::firstOrCreate(
                ['word' =>  $dataTranslation, 'dictionary_id' => Arr::get($data, 'dictionary_id')],
                ['letter' => mb_strtolower(mb_substr($dataTranslation, 0, 1))]
            );
            $newTranslations[] = $translationModel->id;
        }
        $word->translations()->attach($newTranslations);
    }
}
