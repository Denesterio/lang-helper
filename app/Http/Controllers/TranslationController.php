<?php

namespace App\Http\Controllers;

use App\DTO\WordDTO;
use App\Http\Requests\SaveWordRequest;
use App\Http\Resources\WordResource;
use App\Models\Dictionary;
use App\Models\Translation;
use App\Repositories\RepositoryFactory;
use Illuminate\Http\Request;

class TranslationController
{
    protected $class = Translation::class;
    protected $repository;

    public function __construct()
    {
        $this->repository = RepositoryFactory::create()->getRepository($this->class);
    }

    public function update(SaveWordRequest $request, Dictionary $dictionary, Translation $translation): WordResource
    {
        if ($request->user()->cannot('update', $dictionary)) {
            abort(403);
        }

        $data = $request->all();
        return new WordResource($this->repository->update(new WordDTO($data), $translation));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Dictionary $dictionary, Translation $word)
    {
        if (request()->user()->cannot('update', $dictionary)) {
            abort(403);
        }

        $this->repository->destroy($word);
        return response()->json(['data' => ['success' => true]]);
    }
}
