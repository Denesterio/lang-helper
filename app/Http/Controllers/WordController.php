<?php

namespace App\Http\Controllers;

use App\DTO\WordDTO;
use App\Http\Requests\SaveWordRequest;
use App\Http\Resources\WordCollection;
use App\Http\Resources\WordResource;
use App\Models\Dictionary;
use App\Models\Word;
use App\Repositories\RepositoryFactory;
use Illuminate\Http\Request;

class WordController extends Controller
{
    protected $class = Word::class;
    protected $repository;

    public function __construct()
    {
        $this->repository = RepositoryFactory::create()->getRepository($this->class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, string $dictionary): WordCollection
    {
        if ($request->user()->cannot('view', Dictionary::find($dictionary))) {
            abort(403);
        }

        $filters = [
            'dictionary_id' => (int)$dictionary,
        ];

        return new WordCollection($this->repository->index($filters));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveWordRequest $request, Dictionary $dictionary): WordResource
    {
        if ($request->user()->cannot('update', $dictionary)) {
            abort(403);
        }

        $data = $request->all();
        $data['model']['show_at'] = now();
        return new WordResource($this->repository->store($data['model']));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $word = $this->repository->show($id);

        if (request()->user()->cannot('view', $word)) {
            abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaveWordRequest $request, Dictionary $dictionary, Word $word): WordResource
    {
        if ($request->user()->cannot('update', $dictionary)) {
            abort(403);
        }

        $data = $request->all();
        return new WordResource($this->repository->update(new WordDTO($data), $word));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id, Word $word)
    {
        if (request()->user()->cannot('update', $word)) {
            abort(403);
        }

        $this->repository->destroy($word);
        return response()->json(['data' => ['success' => true]]);
    }
}
