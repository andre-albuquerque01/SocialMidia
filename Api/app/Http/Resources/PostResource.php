<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            "idPost" => $this->idPost,
            "imageUrlOne" => $this->imageUrlOne,
            "description" => $this->description,
            "created_at" => $this->created_at,
            "idUser" => $this->user->idUser,
            "firstName" => $this->user->firstName,
            "lastName" => $this->user->lastName,
            "profileUrl" => $this->user->profileUrl,
            "comments" => CommentResource::collection($this->whenLoaded('comments')),
            "rate" => RateResource::collection($this->whenLoaded('rates')),
        ];
    }
}
