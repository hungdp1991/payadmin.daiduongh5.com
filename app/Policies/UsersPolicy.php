<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UsersPolicy
{
    use HandlesAuthorization;

    /**
     * Model instance
     */
    private $usersModel;

    /**
     * Create a new policy instance.
     * @param User $usersModel
     * @return void
     */
    public function __construct(User $usersModel)
    {
        // create model instance
        $this->usersModel = $usersModel;
    }

    /**
     * View users permissions
     * @param User $user
     * @return bool
     */
    public function viewUsers(User $user)
    {
        return $user->hasPermission('ViewUsers');
    }

    /**
     * Create user permission
     * @param User $user
     * @return bool
     */
    public function createUser(User $user)
    {
        return $user->hasPermission('CreateUser');
    }

    /**
     * Update user permission
     * @param User $user
     * @return bool
     */
    public function updateUser(User $user)
    {
        return $user->hasPermission('UpdateUser');
    }

    /**
     * Delete role permission
     * @param User $user
     * @return bool
     */
    public function deleteUser(User $user)
    {
        return $user->hasPermission('DeleteUser')
            && $this->usersModel->find(request()->userId)->removable
            && $user->id !== request()->userId;
    }
}
