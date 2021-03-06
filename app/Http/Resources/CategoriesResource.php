<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'categoryId' => $this->id,
            'categoryParentId' => $this->id_parent,
            'categoryName' => $this->name,
            'categorySlug' => $this->alias,
            'categoryStatus' => $this->status,
            'categoryLevel' => (int) $this->level
        ];
    }
}
