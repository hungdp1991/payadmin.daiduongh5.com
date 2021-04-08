<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GoldPolicy
{
    use HandlesAuthorization;

    /**
     * GamesPolicy constructor.
     */
    public function __construct()
    {

    }

    /**
     * View gold permissions
     * @param User $user
     * @return bool
     */
    public function viewGold(User $user)
    {
        return $user->hasPermission('ViewGold');
    }

    /**
     * Create gold permission
     * @param User $user
     * @return bool
     */
    public function createGold(User $user)
    {
        return $user->hasPermission('CreateGold');
    }

    /**
     * Update gold permission
     * @param User $user
     * @return bool
     */
    public function updateGold(User $user)
    {
        return $user->hasPermission('UpdateGold');
    }

    /**
     * Delete gold permission
     * @param User $user
     * @return bool
     */
    public function deleteGold(User $user)
    {
        return $user->hasPermission('DeleteGold');
    }
}
