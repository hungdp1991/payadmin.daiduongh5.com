<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'postId' => $this->id,
            'postImage' => $this->avatar,
            'postTitle' => $this->title,
            'postSlug' => $this->slug,
            'postIntro' => $this->intro,
            'postContent' => $this->content,
            'postStatus' => $this->status,
            'postCategory' => $this->category
        ];
    }
}
