<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
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
            'userId' => $this->id,
            'username' => $this->username,
            'fullName' => $this->fullname,
            'role' => [
                'roleId' => $this->level,
                'roleName' => $this->role->name,
            ],
            'gamesList' => $this->role_agent !== null ? $this->role_agent : [],
            'email' => $this->email,
            'status' => $this->status,
            'removable' => $this->removable
        ];
    }
}
