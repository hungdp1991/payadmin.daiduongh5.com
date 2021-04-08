<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CardsPolicy
{
    use HandlesAuthorization;

    /**
     * GamesPolicy constructor.
     */
    public function __construct()
    {

    }

    /**
     * View cards permissions
     * @param User $user
     * @return bool
     */
    public function viewCards(User $user)
    {
        return $user->hasPermission('ViewCards');
    }

    /**
     * Create card permission
     * @param User $user
     * @return bool
     */
    public function createCard(User $user)
    {
        return $user->hasPermission('CreateCard');
    }

    /**
     * Update card permission
     * @param User $user
     * @return bool
     */
    public function updateCard(User $user)
    {
        return $user->hasPermission('UpdateCard');
    }

    /**
     * Delete card permission
     * @param User $user
     * @return bool
     */
    public function deleteCard(User $user)
    {
        return $user->hasPermission('DeleteCard');
    }
}
