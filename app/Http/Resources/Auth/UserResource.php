<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'role' => $this->role,
            'company_id' => $this->accountancy_company_id,
            'company' => $this->whenLoaded('accountancyCompany', function () {
                return [
                    'id' => $this->accountancyCompany->id,
                    'name' => $this->accountancyCompany->name,
                    'email' => $this->accountancyCompany->email,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
