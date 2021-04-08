<?php

namespace App\Policies;

use App\Models\Categories;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoriesPolicy
{
    use HandlesAuthorization;

    /**
     * Model instance
     */
    private $categoriesModel;

    /**
     * Create a new policy instance.
     * @param Categories $categoriesModel
     * @return void
     */
    public function __construct(Categories $categoriesModel)
    {
        // create model instance
        $this->categoriesModel = $categoriesModel;
    }

    /**
     * View categories permissions
     * @param User $user
     * @return bool
     */
    public function viewCategories(User $user)
    {
        return $user->hasPermission('ViewCategories');
    }

    /**
     * Create category permission
     * @param User $user
     * @return bool
     */
    public function createCategory(User $user)
    {
        return $user->hasPermission('CreateCategory');
    }

    /**
     * Update category permission
     * @param User $user
     * @return bool
     */
    public function updateCategory(User $user)
    {
        return $user->hasPermission('UpdateCategory');
    }

    /**
     * Delete category permission
     * @param User $user
     * @return bool
     */
    public function deleteCategory(User $user)
    {
        // get current category
        $currentCategory = $this->categoriesModel->getCategoryInfo(request()->categoryId)->load(['categories', 'posts']);

        // count child of current category
        $childCounter = $currentCategory->categories->count();

        // count posts belong to current category
        $postsCounter = $currentCategory->posts->count();

        return $user->hasPermission('DeleteCategory')
                && $childCounter === 0
                && $postsCounter === 0;
    }
}
