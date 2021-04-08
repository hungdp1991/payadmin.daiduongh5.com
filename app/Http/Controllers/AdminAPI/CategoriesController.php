<?php

namespace App\Http\Controllers\AdminAPI;

use App\Http\Controllers\Controller;
use App\Http\Requests\Categories\CategoriesCreatingRequest;
use App\Http\Requests\Categories\CategoriesUpdatingRequest;
use App\Http\Resources\CategoriesResource;
use App\Models\Categories;
use Illuminate\Http\Request;

class CategoriesController extends AdminAPIBaseController
{
    /**
     * Model instance
     */
    private $categoriesModel;

    /**
     * CategoriesController constructor.
     * @param Categories $categoriesModel
     * @created 2020-02-14 LongTHK
     */
    public function __construct(Categories $categoriesModel)
    {
        // call parent constructor
        parent::__construct();

        // create model instance
        $this->categoriesModel = $categoriesModel;
    }

    /**
     * Get parent categories list
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @created 2020-02-14 LongTHK
     */
    public function getParentCategoriesList()
    {
        return CategoriesResource::collection($this->categoriesModel->getCategoriesListByLevel(1));
    }

    /**
     * Get categories list
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function list()
    {
        return CategoriesResource::collection($this->categoriesModel->getCategoriesList());
    }

    /**
     * Create new category
     * @param CategoriesCreatingRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @created 2020-02-14 LongTHK
     */
    public function create(CategoriesCreatingRequest $request)
    {
        // create new category
        $this->categoriesModel->createCategory($request->categoryParentId, $request->categoryName, $request->categorySlug);

        // return response data
        return $this->buildCategoriesResponseData();
    }

    /**
     * Build categories response data
     * @return \Illuminate\Http\JsonResponse
     * @created 2020-02-14 LongTHK
     */
    private function buildCategoriesResponseData()
    {
        return response()->json([
            'categoriesList' => CategoriesResource::collection($this->categoriesModel->getCategoriesList()),
            'parentCategoriesList' => CategoriesResource::collection($this->categoriesModel->getCategoriesListByLevel(1))
        ], 200);
    }

    /**
     * Delete category
     * @return \Illuminate\Http\JsonResponse
     * @created 2020-02-14 LongTHK
     */
    public function delete()
    {
        // delete category
        $this->categoriesModel->deleteCategory(request()->categoryId);

        // return response data
        return $this->buildCategoriesResponseData();
    }

    /**
     * Get category info
     * @return CategoriesResource
     * @created 2020-02-14 LongTHK
     */
    public function info()
    {
        return CategoriesResource::make($this->categoriesModel->getCategoryInfo(request()->categoryId));
    }

    /**
     * Update category
     * @param CategoriesUpdatingRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @created 2020-02-14 LongTHK
     */
    public function update(CategoriesUpdatingRequest $request)
    {
        // update user
        $this->categoriesModel->updateCategory($request->categoryId, $request->categoryParentId, $request->categoryName, $request->categorySlug);

        // return response data
        return $this->buildCategoriesResponseData();
    }
}
