<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServersResource extends JsonResource
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
            'id' => $this->id,
            'serverId' => $this->server_id,
            'serverName' => $this->server_name,
            'serverSlug' => $this->server_slug,
            'serverStatus' => $this->server_status,
            'paymentStatus' => $this->pay_status,
            'keyWebCharge' => $this->key_web_charge,
            'keyIAPCharge' => $this->key_iap_charge,
            'game' => GamesResource::make($this->game)
        ];
    }
}
