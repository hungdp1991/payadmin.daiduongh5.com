<?php

namespace App\Http\Controllers\AdminAPI;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\RolesCreatingRequest;
use App\Http\Requests\Roles\RolesUpdatingRequest;
use App\Http\Resources\RolesResource;
use App\Models\Roles;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RolesController extends AdminAPIBaseController
{
    /**
     * Model instance
     */
    private $rolesModel;

    /**
     * RolesController constructor.
     * @param Roles $rolesModel
     */
    public function __construct(Roles $rolesModel)
    {
        // call parent constructor
        parent::__construct();

        // create model instance
        $this->rolesModel = $rolesModel;
    }

    /**
     * Get permissions list
     * @return JsonResponse
     * @created 2020-02-12
     */
    public function getPermissionsList()
    {
        return response()->json(config('permissionslist'));
    }

    /**
     * Get paginated roles list
     * @return AnonymousResourceCollection
     */
    public function list()
    {
        // get current page
        $currentPage = request()->currentPage;

        // return collection
        return RolesResource::collection($this->rolesModel->getRolesList($currentPage))->additional([
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPage' => $this->rolesModel->getTotalPage()
            ]
        ]);
    }

    /**
     * Create new role and return new roles list
     * @param RolesCreatingRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @created 2020-02-12
     */
    public function create(RolesCreatingRequest $request)
    {
        // create new customer
        $this->rolesModel->createRole($request->roleName, $request->permissionsList);
        // get current page
        $currentPage = $request->currentPage;

        // return new customers list
        return RolesResource::collection($this->rolesModel->getRolesList($currentPage))->additional([
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPage' => $this->rolesModel->getTotalPage()
            ]
        ]);
    }

    /**
     * Delete role
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @created 2020-02-12
     */
    public function delete()
    {
        // delete customer
        $this->rolesModel->deleteRole(request()->roleId);
        // get current page
        $currentPage = request()->currentPage;

        // return new customers list
        return RolesResource::collection($this->rolesModel->getRolesList($currentPage))->additional([
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPage' => $this->rolesModel->getTotalPage()
            ]
        ]);
    }

    /**
     * Get role info
     * @return RolesResource
     * @created 2020-02-12 LongTHK
     */
    public function info()
    {
        return RolesResource::make($this->rolesModel->getRoleInfo(request()->roleId));
    }

    /**
     * Update role
     * @param RolesUpdatingRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function update(RolesUpdatingRequest $request)
    {
        // update customer
        $this->rolesModel->updateRole($request->roleId, $request->roleName, $request->permissionsList);
        // get current page
        $currentPage = $request->currentPage;

        // return new customers list
        return RolesResource::collection($this->rolesModel->getRolesList($currentPage))->additional([
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPage' => $this->rolesModel->getTotalPage()
            ]
        ]);
    }

    /**
     * Get all customers
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @created 2020-02-13 LongTHK
     */
    public function all()
    {
        // return collection
        return RolesResource::collection($this->rolesModel->getAll());
    }
}
