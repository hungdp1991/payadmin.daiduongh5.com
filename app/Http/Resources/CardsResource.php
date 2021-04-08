<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CardsResource extends JsonResource
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
            'cardId' => $this->id,
            'cardType' => $this->type,
            'cardName' => $this->name,
            'cardStatus' => $this->status,
        ];
    }
}
