<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SlidersResource extends JsonResource
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
            'sliderId' => $this->id,
            'sliderName' => $this->name,
            'sliderDescription' => $this->description,
            'sliderItemsList' => $this->items,
            'isDefault' => $this->is_default
        ];
    }
}
