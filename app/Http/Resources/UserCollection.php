<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class UserCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d'),
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d'),
            'role' => ['id' => $this->role->id, 'name' => $this->role->name,],
            'school' => ['id' => $this->school->id, 'name' => $this->school->name],
        ];
    }
}
