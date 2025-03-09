<?php

namespace App\Repositories;

use App\Models\Language;
use Illuminate\Database\Eloquent\Collection;

class LanguageRepository
{
    private $class = Language::class;

    public function index(): Collection
    {
        return $this->class::all();
    }
}
