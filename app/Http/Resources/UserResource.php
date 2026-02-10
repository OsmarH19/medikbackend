<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'current_team_id' => $this->current_team_id,
            'profile_photo_path' => $this->profile_photo_path,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'personal_id' => $this->personal_id,
            'state' => $this->state,
            'photo' => $this->photo,
            'last_signIn_at' => $this->last_signIn_at,
            'users_grupo_id' => $this->users_grupo_id,
            'users_roles_id' => $this->users_roles_id,
            'tokendigital' => $this->tokendigital,
            'tokendigitaldt' => $this->tokendigitaldt,
            'passsecure' => $this->passsecure,
            'roles_id' => $this->roles_id,
            'id_samk' => $this->id_samk,
            'skip_two_factor' => $this->skip_two_factor,
            'personal' => $this->whenLoaded('personal', fn () => new PersonalResource($this->personal)),
        ];
    }
}
