<?php

namespace App\Http\Controllers;

use App\Http\Resources\LanguageCollection;
use App\Models\Language;
use App\Repositories\RepositoryFactory;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    protected $class = Language::class;
    protected $repository;

    public function __construct()
    {
        $this->repository = RepositoryFactory::create()->getRepository($this->class);
    }

    public function index(): LanguageCollection
    {
        return new LanguageCollection($this->repository->index());
    }
}
