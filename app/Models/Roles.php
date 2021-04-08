<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Roles extends Model
{
    /**
     * Model properties
     * @var bool
     */
    public $incrementing = false;
    public $timestamps = false;
    protected $table = 'roles';
    protected $primaryKey = 'id';
    protected $perPage = 10;
    protected $casts = [
        'permissions' => 'array',
        'removable' => 'boolean'
    ];

    /**
     * Create Roles - Users relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @created 2020-03-03 LongTHK
     */
    public function users()
    {
        return $this->hasMany(User::class, 'level', 'id');
    }

    /**
     * Get roles depend on page
     * @param $currentPage
     * @return mixed
     * @created 2020-02-12
     */
    public function getRolesList($currentPage = 1)
    {
        return $this->orderBy('name')
            ->skip(($currentPage - 1) * $this->perPage)
            ->take($this->perPage)
            ->get();
    }

    /**
     * Get total page after paginate
     * @return float
     * @created 2020-02-12
     */
    public function getTotalPage()
    {
        return ceil($this->count() / $this->perPage);
    }

    /**
     * Create new role
     * @param $roleName
     * @param $permissionsList
     * @created LongTHK 2020-02-12
     */
    public function createRole($roleName, $permissionsList)
    {
        // create new instance
        $newRole = new Roles();

        // set info
        $newRole->name = $roleName;
        $newRole->permissions = $permissionsList;

        // save
        $newRole->save();
    }

    /**
     * Delete role
     * @param $roleId
     * @return bool|void|null
     * @created LongTHK 2020-02-12
     */
    public function deleteRole($roleId)
    {
        $this->find($roleId)->delete();
    }

    /**
     * Get role info
     * @param $roleId
     * @return mixed
     * @created LongTHK 2020-02-12
     */
    public function getRoleInfo($roleId)
    {
        return $this->find($roleId);
    }

    /**
     * Update customer info
     * @param $roleId
     * @param $roleName
     * @param $permissionsList
     * @created LongTHK 2020-02-12
     * @return bool|void
     */
    public function updateRole($roleId, $roleName, $permissionsList)
    {
        // get role by id
        $role = $this->find($roleId);

        // set customer info
        $role->name = $roleName;
        $role->permissions = $permissionsList;

        // save
        $role->save();
    }

    /**
     * Get all roles
     * @return mixed
     * @created 2020-02-13 LongTHK
     */
    public function getAll()
    {
        return $this->orderBy('name')
            ->get();
    }
}
