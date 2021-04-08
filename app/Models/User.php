<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    public $incrementing = true;
    public $timestamps = true;
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $casts = [
        'status' => 'boolean',
        'role_agent' => 'array'
    ];

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * Create Role - User relation
     * @created 2020-02-13 LongTHK
     * @return BelongsTo
     *
     */
    public function role()
    {
        return $this->belongsTo(Roles::class, 'level', 'id');
    }

    /**
     * Get user info by given username and password
     * @param $username
     * @param $password
     * @return mixed
     * @created LongTHK 2020-02-11
     */
    public function getUserByUsernameAndPassword($username, $password)
    {
        return $this->firstWhere([
            ['username', '=', $username],
            ['password', '=', $password]
        ]);
    }

    /**
     * Change user password
     * @param $userId
     * @param $newPassword
     */
    public function changePassword($userId, $newPassword)
    {
        // get current user
        $user = $this->find($userId);

        // set new password
        $user->password = md5($newPassword);

        // save
        $user->save();
    }

    /**
     * Get users depend on page
     * @param $currentPage
     * @return mixed
     * @created 2020-02-13 LongTHK
     */
    public function getUsersList($currentPage = 1)
    {
        return $this->orderBy('username')
            ->skip(($currentPage - 1) * $this->perPage)
            ->take($this->perPage)
            ->get();
    }

    /**
     * Get total page after paginate
     * @return float
     * @created 2020-02-13 LongTHK
     */
    public function getTotalPage()
    {
        return ceil($this->count() / $this->perPage);
    }

    /**
     * Create new user
     * @param $fullName
     * @param $username
     * @param $password
     * @param $email
     * @param $roleId
     * @created 2020-02-13 LongTHK
     */
    public function createUser($fullName, $username, $password, $email, $roleId, $gamesList)
    {
        // create new instance
        $newUser = new User();

        // set info
        $newUser->fullname = $fullName;
        $newUser->username = $username;
        $newUser->password = md5($password);
        $newUser->email = $email;
        $newUser->level = $roleId;
        $newUser->role_agent = $gamesList;

        // save
        $newUser->save();
    }

    /**
     * Delete user
     * @param $userId
     * @return bool|void|null
     * @created 2020-02-13 LongTHK
     */
    public function deleteUser($userId)
    {
        $this->find($userId)->delete();
    }

    /**
     * Get user info
     * @param $userId
     * @return mixed
     * @created 2020-02-13 LongTHK
     */
    public function getUserInfo($userId)
    {
        return $this->find($userId);
    }

    /**
     * Update user info
     * @param $userId
     * @param $fullName
     * @param $roleId
     * @param $gamesList
     * @return bool|void
     * @created 2020-02-13 LongTHK
     */
    public function updateRole($userId, $fullName, $email, $roleId, $gamesList)
    {
        // get user by id
        $user = $this->find($userId);

        // set user info
        $user->fullname = $fullName;
        $user->email = $email;
        $user->level = $roleId;
        $user->role_agent = $gamesList;

        // save
        $user->save();
    }

    /**
     * Update user password
     * @param $userId
     * @param $newPassword
     * @created 2020-02-13 LongTHK
     */
    public function updatePassword($userId, $newPassword)
    {
        // get user by id
        $user = $this->find($userId);

        // set new password
        $user->password = md5($newPassword);

        // save
        $user->save();
    }

    /**
     * Check user has permission through role
     * @param $permissionName
     * @return bool
     * @created 2020-03-03 LongTHK
     */
    public function hasPermission($permissionName)
    {
        return in_array($permissionName, $this->role->permissions);
    }
}
