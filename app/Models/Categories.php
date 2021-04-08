<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    public $incrementing = true;
    public $timestamps = true;
    protected $table = 'category';
    protected $primaryKey = 'id';
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Create Categories - Categories relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany(Categories::class, 'id_parent', 'id');
    }

    /**
     * Create Categories - Post relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Posts::class, 'id_category', 'id');
    }

    /**
     * Get categories list by given level value
     * @param $level
     * @return mixed
     * @created 2020-02-14 LongTHK
     */
    public function getCategoriesListByLevel($level)
    {
        return $this->where([
            ['level', '=', $level]
        ])->get();
    }

    /**
     * Get categories list
     * @return mixed
     * @created 2020-02-14 LongTHK
     */
    public function getCategoriesList()
    {
        return $this->orderBy('level')
            ->get();
    }

    /**
     * Create new category
     * @param $parentCategoryId
     * @param $categoryName
     * @param $categorySlug
     */
    public function createCategory($parentCategoryId, $categoryName, $categorySlug)
    {
        // create new category instance
        $newCategory = new Categories();

        // set category info
        $newCategory->id_parent = $parentCategoryId;
        $newCategory->name = $categoryName;
        $newCategory->alias = $categorySlug;
        $newCategory->level = $this->generateCategoryLevel($parentCategoryId);

        // save
        $newCategory->save();
    }

    /**
     * Generate category level depend on parent category id
     * @param $parentCategoryId
     * @return int
     * @created 2020-02-14 LongTHK
     */
    private function generateCategoryLevel($parentCategoryId)
    {
        return ($parentCategoryId === config('constants.ROOT_CATEGORY_ID')) ? 1 : 2;
    }

    /**
     * Delete category
     * @param $categoryId
     * @return bool|void|null
     * @created 2020-02-14 LongTHK
     */
    public function deleteCategory($categoryId)
    {
        $this->find($categoryId)->delete();
    }

    /**
     * Get category info
     * @param $categoryId
     * @return mixed
     * @created 2020-02-14 LongTHK
     */
    public function getCategoryInfo($categoryId)
    {
        return $this->find($categoryId);
    }

    /**
     * Update user info
     * @param $categoryId
     * @param $parentCategoryId
     * @param $categoryName
     * @param $categorySlug
     * @return bool|void
     * @created 2020-02-13 LongTHK
     */
    public function updateCategory($categoryId, $parentCategoryId, $categoryName, $categorySlug)
    {
        // get category by id
        $category = $this->find($categoryId);

        // set category info
        $category->id_parent = $parentCategoryId;
        $category->name = $categoryName;
        $category->alias = $categorySlug;
        $category->level = $this->generateCategoryLevel($parentCategoryId);

        // save
        $category->save();
    }
}
