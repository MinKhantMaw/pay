<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationDetailResource extends JsonResource
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
            'title' => $this->data['title'],
            'message' => $this->data['message'],
            'read_time' => Carbon::parse($this->created_at)->format('Y-m-d h:i:s A'),
            'deep_link' => $this->data['deep_link']

        ];
    }
}
