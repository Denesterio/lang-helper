<?php

namespace App\Http\Controllers;

use App\DTO\WordDTO;
use App\Http\Resources\WordCollection;
use App\Http\Resources\WordResource;
use App\Models\Dictionary;
use App\Models\Translation;
use App\Models\Word;
use App\Repositories\RepositoryFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    public function getButtons(): JsonResponse
    {
        $data = [
            ['value' => 20, 'name' => 'Плохо'],
            ['value' => 68, 'name' => 'Хорошо'],
            ['value' => 140, 'name' => 'Отлично']
        ];

        return response()->json(compact('data'));
    }

    public function getWords(Request $request): WordCollection
    {
        $dict = $request->query('dictionaryId');

        if ($request->user()->cannot('view', Dictionary::find($dict))) {
            abort(403);
        }

        $data = RepositoryFactory::create()->getRepository(Word::class)->filterProcess($dict);
        return new WordCollection($data);
    }

    public function getTranslations(Request $request): WordCollection
    {
        $dict = $request->query('dictionaryId');

        if ($request->user()->cannot('view', Dictionary::find($dict))) {
            abort(403);
        }

        $data = RepositoryFactory::create()->getRepository(Translation::class)->filterProcess($dict);
        return new WordCollection($data);
    }
}
