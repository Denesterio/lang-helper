<?php

namespace App\Repositories;

use App\Models\Dictionary;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class DictionaryRepository
{
    private $class = Dictionary::class;

    /**
     * Display a listing of the resource.
     */
    public function index(array $filters): Collection
    {
        $relations = ['language_from', 'language_to'];

        if (empty(Arr::get($filters, 'id'))) {
            return $this->class::with($relations)->get();
        }

        $query = $this->class::query();
        if (!empty(Arr::get($filters, 'id'))) {
            $query = $query->whereIn('id', $filters['id']);
        }
        if (!empty(Arr::get($filters, 'user_id'))) {
            $query = $query->where('user_id', $filters['user_id']);
        }

        return $query->with($relations)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(array $data): Dictionary
    {
        return $this->class::create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): ?Dictionary
    {
        return $this->class::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id, array $data)
    {
        $dict = $this->show($id);
        $dict->fill($data);
        $dict->save();
        return $dict;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): bool
    {
        $model = $this->class::find($id);
        $model->words()->delete();
        $model->translations()->delete();
        return (bool)$model->delete();
    }
}
