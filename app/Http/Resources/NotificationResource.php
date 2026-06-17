<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => Str::limit($this->data['title'], 40, '...'),
            'message' => Str::limit($this->data['message'], 100, '...'),
            'date_time' => Carbon::parse($this->created_at)->format('Y-m-d h:i:s A'),
            'read' => ! is_null($this->read_at) ? 1 : 0,
        ];
    }
}
