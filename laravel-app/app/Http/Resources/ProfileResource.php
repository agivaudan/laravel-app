<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Profile;

/**
 * @property Profile $resource
 */
class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->resource->id,
            'first_name'    => $this->resource->first_name,
            'last_name'     => $this->resource->last_name,
            'image'         => $this->resource->image,
            'status'        => $this->when(false, $this->resource->status), // TODO put true if user is admin
            'user'          => $this->resource->user,
        ];
    }
}
