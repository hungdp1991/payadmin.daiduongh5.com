<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostsPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {

    }

    /**
     * View posts permissions
     * @param User $user
     * @return bool
     */
    public function viewPosts(User $user)
    {
        return $user->hasPermission('ViewPosts');
    }

    /**
     * Create post permission
     * @param User $user
     * @return bool
     */
    public function createPost(User $user)
    {
        return $user->hasPermission('CreatePost');
    }

    /**
     * Update post permission
     * @param User $user
     * @return bool
     */
    public function updatePost(User $user)
    {
        return $user->hasPermission('UpdatePost');
    }

    /**
     * Delete post permission
     * @param User $user
     * @return bool
     */
    public function deletePost(User $user)
    {
        return $user->hasPermission('DeletePost');
    }
}
