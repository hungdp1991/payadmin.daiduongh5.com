<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GoldResource extends JsonResource
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
            'goldId' => $this->id,
            'goldName' => $this->name,
            'goldDescription' => $this->description,
            'goldImage' => $this->image,
            'goldAmount' => $this->amount,
            'goldValue' => $this->gold,
            'productGoldId' => $this->product_gold_id,
            'goldType' => $this->card_month,
            'game' => GamesResource::make($this->game)
        ];
    }
}
