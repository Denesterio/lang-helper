<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    private $class = User::class;

    /**
     * Display a listing of the resource.
     */
    public function index(array $ids = []): Collection
    {
        if (empty($ids)) {
            return $this->class::all();
        }

        return $this->class::find($ids);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(array $data): User
    {
        return $this->class::create([
            'name' => $data['name'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): ?User
    {
        return $this->class::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(array $data, int $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        //
    }
}
