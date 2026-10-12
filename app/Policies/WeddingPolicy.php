<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wedding;

class WeddingPolicy
{
    /**
     * Determine whether the user can view the wedding.
     */
    public function view(User $user, Wedding $wedding): bool
    {
        return $user->id === $wedding->user_id;
    }

    /**
     * Determine whether the user can update the wedding.
     */
    public function update(User $user, Wedding $wedding): bool
    {
        return $user->id === $wedding->user_id;
    }

    /**
     * Determine whether the user can delete the wedding.
     */
    public function delete(User $user, Wedding $wedding): bool
    {
        return $user->id === $wedding->user_id;
    }

    /**
     * Determine whether the user can manage resources inside the wedding (events, couple, galleries, guests).
     */
    public function manage(User $user, Wedding $wedding): bool
    {
        return $user->id === $wedding->user_id;
    }
}
