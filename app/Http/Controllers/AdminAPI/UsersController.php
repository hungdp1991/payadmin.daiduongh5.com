<?php

namespace App\Http\Controllers\AdminAPI;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\Users\UsersCreatingRequest;
use App\Http\Requests\Users\UsersUpdatingRequest;
use App\Http\Resources\UsersResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UsersController extends AdminAPIBaseController
{
    /**
     * Model instance
     */
    private $usersModel;

    /**
     * UsersController constructor.
     * @param User $usersModel
     */
    public function __construct(User $usersModel)
    {
        // call parent constructor
        parent::__construct();

        // create model instance
        $this->usersModel = $usersModel;
    }

    /**
     * Check authentication status
     * Return user info in case of having authenticated user
     * @return \Illuminate\Http\JsonResponse
     * @created LongTHK 2020-02-11
     */
    public function checkAuthenticationStatus()
    {
        return response()->json([
            'userInfo' => auth()->user()->load('role')
        ], 200);
    }

    /**
     * Authenticate
     * Return user info and token key
     * @created LongTHK 2020-02-11
     */
    public function authenticate()
    {
        // get user info
        $userInfo = $this->usersModel->getUserByUsernameAndPassword(\request()->username, request()->password);

        // check user
        if (empty($userInfo)
            || (!empty($userInfo) && !$userInfo->status)) {
            return response()->json([], 401);
        } else {
            auth()->login($userInfo);
            return response()->json([
                'userInfo' => auth()->user()->load('role')
            ], 200);
        }
    }

    /**
     * Change password
     * @param ChangePasswordRequest $request
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        // change password
        $this->usersModel->changePassword($request->user()->id, $request->newPassword);
    }

    /**
     * Logout
     */
    public function logout()
    {
        auth()->logout();
    }

    /**
     * Get paginated users list
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @created 2020-02-13 LongTHK
     */
    public function list()
    {
        // get current page
        $currentPage = request()->currentPage;

        // return collection
        return UsersResource::collection($this->usersModel->getUsersList($currentPage))->additional([
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPage' => $this->usersModel->getTotalPage()
            ]
        ]);
    }

    /**
     * Create new user and return new users list
     * @param UsersCreatingRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @created 2020-02-13 LongTHK
     */
    public function create(UsersCreatingRequest $request)
    {
        // create new customer
        $this->usersModel->createUser($request->fullName, $request->username, $request->password, $request->email, $request->roleId, $request->gamesList);
        // get current page
        $currentPage = $request->currentPage;

        // return new users list
        return UsersResource::collection($this->usersModel->getUsersList($currentPage))->additional([
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPage' => $this->usersModel->getTotalPage()
            ]
        ]);
    }

    /**
     * Delete user
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @created 2020-02-13 LongTHK
     */
    public function delete()
    {
        // delete customer
        $this->usersModel->deleteUser(request()->userId);
        // get current page
        $currentPage = request()->currentPage;

        // return new customers list
        return UsersResource::collection($this->usersModel->getUsersList($currentPage))->additional([
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPage' => $this->usersModel->getTotalPage()
            ]
        ]);
    }

    /**
     * Get user info
     * @return UsersResource
     * @created 2020-02-13 LongTHK
     */
    public function info()
    {
        return UsersResource::make($this->usersModel->getUserInfo(request()->userId));
    }

    /**
     * Update user
     * @param UsersUpdatingRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @created 2020-02-13 LongTHK
     */
    public function update(UsersUpdatingRequest $request)
    {
        // update user
        $this->usersModel->updateRole($request->userId, $request->fullName, $request->email, $request->roleId, $request->gamesList);
        // get current page
        $currentPage = $request->currentPage;

        // return new customers list
        return UsersResource::collection($this->usersModel->getUsersList($currentPage))->additional([
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPage' => $this->usersModel->getTotalPage()
            ]
        ]);
    }

    /**
     * Reset user password
     * @return \Illuminate\Http\JsonResponse
     * @created 2020-02-13 LongTHK
     */
    public function resetPassword()
    {
        // generate new password
        $newPassword = Str::random(8);

        // update user password
        $this->usersModel->updatePassword(request()->userId, $newPassword);

        return response()->json($newPassword, 200);
    }
}
