<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveDictionaryRequest;
use App\Http\Resources\DictionaryCollection;
use App\Http\Resources\DictionaryResource;
use App\Models\Dictionary;
use App\Repositories\RepositoryFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DictionaryController extends Controller
{
    protected $class = Dictionary::class;
    protected $repository;

    public function __construct()
    {
        $this->repository = RepositoryFactory::create()->getRepository($this->class);
    }

    public function index(): DictionaryCollection
    {
        $filters = [
            'user_id' => Auth::id(),
        ];
        return new DictionaryCollection($this->repository->index($filters));
    }

    public function store(SaveDictionaryRequest $request): DictionaryResource
    {
        $data = $request->validated()['model'];
        $data['user_id'] = Auth::id();
        return new DictionaryResource($this->repository->store($data));
    }

    public function show(string $id): DictionaryResource
    {
        return new DictionaryResource($this->repository->show($id));
    }

    public function update(SaveDictionaryRequest $request, string $id): DictionaryResource
    {
        $data = $request->validated()['model'];
        $dict = $this->repository->update((int)$id, $data);
        return new DictionaryResource($dict);
    }

    public function destroy(string $id): JsonResponse
    {
        $success = $this->repository->destroy($id);
        return response()->json(['data' => ['success' => $success]]);
    }
}
