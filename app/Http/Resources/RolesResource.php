<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RolesResource extends JsonResource
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
            'roleId' => $this->id,
            'roleName' => $this->name,
            'permissionsList' => $this->permissions,
            'removable' => $this->removable,
            'usersCounter' => $this->users->count()
        ];
    }
}
