<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
public function toArray($request)
{
    return [
        'id'        => $this->id,
        'title'     => $this->title,
        'done'      => (bool) $this->done,
        'order'     => $this->order,
        'createdAt' => $this->created_at?->toIso8601String(),
        'updatedAt' => $this->updated_at?->toIso8601String(),
    ];
}

}
