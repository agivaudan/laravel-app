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
        $isAdmin = false;
        
        if(\Auth::check()) {
            $user = \Auth::user();
            $isAdmin = $user->type === 'ADMIN';
        }

        return [
            'id'            => $this->resource->id,
            'first_name'    => $this->resource->first_name,
            'last_name'     => $this->resource->last_name,
            'image'         => $this->resource->image,
            'status'        => $this->when($isAdmin, $this->resource->status), /* field only if the user is admin */ 
            'user'          => $this->resource->user,
        ];
    }
}
