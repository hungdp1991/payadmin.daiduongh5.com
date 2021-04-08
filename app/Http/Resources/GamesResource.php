<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GamesResource extends JsonResource
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
            'gameId' => $this->id,
            'gameAgent' => $this->agent,
            'gameName' => $this->name,
            'gameSlug' => $this->slug,
            'gameImage' => $this->avatar,
            'gameStatus' => $this->status,
            'paymentType' => $this->payment_type,
            'acceptedChargeType' => $this->accepted_charge_type,
            'gameRedirect' => $this->url_redirect,
            'limitIPList' => $this->limit_local
        ];
    }
}
