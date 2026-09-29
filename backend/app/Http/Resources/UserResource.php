<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

// Define the JSON representation of a user.
//
// Return safe public information such as:
// - ID
// - name
// - email
// - role
//
// Never expose password hashes,
// authentication tokens,
// or other sensitive fields.
class UserResource extends JsonResource
{
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
            'role' => $this->role?->value ?? (string) $this->role,
            'role_label' => method_exists($this->role, 'label') ? $this->role->label() : null,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
