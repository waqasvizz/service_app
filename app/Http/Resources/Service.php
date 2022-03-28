<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Service extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);

        // return [
        //     'id' => $this->id,
        //     'name' => $this->name,
        //     'address' => $this->address,
        //     'zip_code' => $this->zip_code,
        //     'phone_number' => $this->phone_number,
        //     'website_link' => $this->website_link,
        //     'created_at' => $this->created_at->format('d/m/Y'),
        //     'updated_at' => $this->updated_at->format('d/m/Y'),
        // ];
    }
}