<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IssueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'initiator_name' => $this->initiator_name,
            'text' => $this->text,
            'images_src' => ImageResource::collection($this->images),
            'initiator_contact' => $this->initiator_contact,
            'initiator_anydesk' => $this->initiator_anydesk,
            'status' => $this->status,
            'status_type_name' => $this->status_type_name,
            'taken_at' => $this->taken_at,
            'category_id' => $this->category_id,
            'dispatcher_id' => $this->dispatcher_id,


        ];
    }
}
