<?php

namespace App\Policies;

use App\Models\Roles;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolesPolicy
{
    use HandlesAuthorization;

    /**
     * Model instance
     */
    private $rolesModel;

    /**
     * Create a new policy instance.
     * @param Roles $rolesModel
     * @return void
     */
    public function __construct(Roles $rolesModel)
    {
        // create model instance
        $this->rolesModel = $rolesModel;
    }

    /**
     * View roles permissions
     * @param User $user
     * @return bool
     */
    public function viewRoles(User $user)
    {
        return $user->hasPermission('ViewRoles');
    }

    /**
     * Create role permission
     * @param User $user
     * @return bool
     */
    public function createRole(User $user)
    {
        return $user->hasPermission('CreateRole');
    }

    /**
     * Update role permission
     * @param User $user
     * @return bool
     */
    public function updateRole(User $user)
    {
        return $user->hasPermission('UpdateRole');
    }

    /**
     * Delete role permission
     * @param User $user
     * @return bool
     */
    public function deleteRole(User $user)
    {
        // get current role
        $currentRole = $this->rolesModel->getRoleInfo(request()->roleId)->load('users');

        // get current role removable status
        $removableStatus = $currentRole->removable;

        // count user belong to current role
        $userCounter = $currentRole->users->count();

        return $user->hasPermission('DeleteRole')
            && $removableStatus
            && $userCounter === 0;
    }
}
