<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    public $incrementing = true;
    public $timestamps = true;
    protected $table = 'posts';
    protected $primaryKey = 'id';
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Post - Category relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @created 2020-02-17 LongTHK
     */
    public function category()
    {
        return $this->belongsTo(Categories::class, 'id_category', 'id');
    }

    /**
     * Get posts list depend on page
     * @param $currentPage
     * @return mixed
     * @created 2020-02-17 LongTHK
     */
    public function getPostsList($currentPage = 1)
    {
        return $this->orderByDesc('created_at')
            ->skip(($currentPage - 1) * $this->perPage)
            ->take($this->perPage)
            ->get();
    }

    /**
     * Get total page after paginate
     * @return float
     * @created 2020-02-17 LongTHK
     */
    public function getTotalPage()
    {
        return ceil($this->count() / $this->perPage);
    }

    /**
     * Delete post by given post id
     * @param $postId
     * @created 2020-02-17 LongTHK
     */
    public function deletePost($postId)
    {
        $this->find($postId)->delete();
    }

    /**
     * Create new post
     * @param $categoryId
     * @param $postTitle
     * @param $postSlug
     * @param $postImage
     * @param $postIntro
     * @param $postContent
     * @created 2020-02-17 LongTHK
     */
    public function createPost($categoryId, $postTitle, $postSlug, $postImage, $postIntro, $postContent)
    {
        // new instance
        $newPost = new Posts();

        // set post info
        $newPost->id_category = $categoryId;
        $newPost->title = $postTitle;
        $newPost->slug = $postSlug;
        $newPost->avatar = $postImage;
        $newPost->intro = $postIntro;
        $newPost->content = $postContent;

        // save
        $newPost->save();
    }

    /**
     * Get post info
     * @param $postId
     * @return mixed
     * @created 2020-02-17 LongTHK
     */
    public function getPostInfo($postId)
    {
        return $this->find($postId);
    }

    /**
     * Update post
     * @param $postId
     * @param $categoryId
     * @param $postTitle
     * @param $postSlug
     * @param $postImage
     * @param $postIntro
     * @param $postContent
     * @created 2020-02-17 LongTHK
     */
    public function updatePost($postId, $categoryId, $postTitle, $postSlug, $postImage, $postIntro, $postContent)
    {
        // get post info
        $postInfo = $this->find($postId);

        // set new post info
        $postInfo->id_category = $categoryId;
        $postInfo->title = $postTitle;
        $postInfo->slug = $postSlug;
        $postInfo->avatar = $postImage;
        $postInfo->intro = $postIntro;
        $postInfo->content = $postContent;

        // save
        $postInfo->save();
    }
}
