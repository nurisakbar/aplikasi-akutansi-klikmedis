<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChartOfAccountResource extends JsonResource
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
            'code' => $this->code,
            'name' => $this->name,
            'type' => $this->type,
            'category' => $this->category,
            'formatted_category' => $this->formatted_category,
            'parent_id' => $this->parent_id,
            'parent' => $this->when($this->parent, function() {
                return [
                    'id' => $this->parent->id,
                    'code' => $this->parent->code,
                    'name' => $this->parent->name,
                ];
            }),
            'description' => $this->description,
            'is_active' => $this->is_active,
            'level' => $this->level,
            'path' => $this->path,
            'children_count' => $this->when(isset($this->children_count), $this->children_count),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 