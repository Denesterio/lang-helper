<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Word;

class WordPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {

    }

    private function check(User $user, Word $word): bool
    {
        return $user->id === $word->dictionary->user_id;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Word $word): bool
    {
        return $this->check($user, $word);
    }

    public function update(User $user, Word $word): bool
    {
        return $this->check($user, $word);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function delete(User $user, Word $word): bool
    {
        return $this->check($user, $word);
    }

    public function forceDelete(User $user, Word $word): bool
    {
        return $this->check($user, $word);
    }
}
