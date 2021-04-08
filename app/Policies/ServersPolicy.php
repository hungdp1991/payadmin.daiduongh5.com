<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServersPolicy
{
    use HandlesAuthorization;

    /**
     * GamesPolicy constructor.
     */
    public function __construct()
    {

    }

    /**
     * View servers permissions
     * @param User $user
     * @return bool
     */
    public function viewServers(User $user)
    {
        return $user->hasPermission('ViewServers');
    }

    /**
     * Create servers permission
     * @param User $user
     * @return bool
     */
    public function createServer(User $user)
    {
        return $user->hasPermission('CreateServer');
    }

    /**
     * Update server permission
     * @param User $user
     * @return bool
     */
    public function updateServer(User $user)
    {
        return $user->hasPermission('UpdateServer');
    }

    /**
     * Delete server permission
     * @param User $user
     * @return bool
     */
    public function deleteServer(User $user)
    {
        return $user->hasPermission('DeleteServer');
    }
}
