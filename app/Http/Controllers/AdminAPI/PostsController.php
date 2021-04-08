<?php

namespace App\Http\Controllers\AdminAPI;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\PostsCreatingRequest;
use App\Http\Requests\Posts\PostsUpdatingRequest;
use App\Http\Resources\PostsResource;
use App\Models\Posts;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostsController extends AdminAPIBaseController
{
    /**
     * Model instance
     */
    private $postsModel;

    /**
     * PostsController constructor.
     * @param Posts $postsModel
     * @created 2020-02-17 LongTHK
     */
    public function __construct(Posts $postsModel)
    {
        // call parent constructor
        parent::__construct();

        // create model instance
        $this->postsModel = $postsModel;
    }

    /**
     * Get news list
     * @return AnonymousResourceCollection
     * @created 2020-02-17 LongTHK
     */
    public function list()
    {
        // get current page
        $currentPage = request()->currentPage;

        // return collection
        return PostsResource::collection($this->postsModel->getPostsList($currentPage))->additional([
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPage' => $this->postsModel->getTotalPage()
            ]
        ]);
    }

    /**
     * Delete post
     * @return AnonymousResourceCollection
     * @created 2020-02-17 LongTHK
     */
    public function delete()
    {
        // get post id
        $postId = request()->postId;
        // get current page
        $currentPage = request()->currentPage;

        // delete post
        $this->postsModel->deletePost($postId);

        // return collection
        return PostsResource::collection($this->postsModel->getPostsList())->additional([
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPage' => $this->postsModel->getTotalPage()
            ]
        ]);
    }

    /**
     * Create new post
     * @param PostsCreatingRequest $request
     * @return AnonymousResourceCollection
     * @created 2020-02-17 LongTHK
     */
    public function create(PostsCreatingRequest $request)
    {
        // get post info
        $categoryId = $request->categoryId;
        $postTitle = $request->postTitle;
        $postSlug = $request->postSlug;
        $postImage = $request->postImage;
        $postIntro = $request->postIntro;
        $postContent = $request->postContent;

        // create new post
        $this->postsModel->createPost($categoryId, $postTitle, $postSlug, $postImage, $postIntro, $postContent);

        // return collection
        return PostsResource::collection($this->postsModel->getPostsList())->additional([
            'pagination' => [
                'currentPage' => 1,
                'totalPage' => $this->postsModel->getTotalPage()
            ]
        ]);
    }

    /**
     * Get post info
     * @return PostsResource
     * @created 2020-02-17 LongTHK
     */
    public function info()
    {
        return PostsResource::make($this->postsModel->getPostInfo(request()->postId));
    }

    /**
     * Update post
     * @param PostsUpdatingRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @created 2020-02-17 LongTHK
     */
    public function update(PostsUpdatingRequest $request)
    {
        // get post info
        $postId = $request->postId;
        $categoryId = $request->categoryId;
        $postTitle = $request->postTitle;
        $postSlug = $request->postSlug;
        $postImage = $request->postImage;
        $postIntro = $request->postIntro;
        $postContent = $request->postContent;

        // create new post
        $this->postsModel->updatePost($postId, $categoryId, $postTitle, $postSlug, $postImage, $postIntro, $postContent);

        // return collection
        return PostsResource::collection($this->postsModel->getPostsList())->additional([
            'pagination' => [
                'currentPage' => 1,
                'totalPage' => $this->postsModel->getTotalPage()
            ]
        ]);
    }
}
