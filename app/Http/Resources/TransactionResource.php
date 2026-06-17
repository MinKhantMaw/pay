<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $title = '';
        if ($this->type == 1) {
            $title = 'From '.$this->source->name;
        } elseif ($this->type == 2) {
            $title = 'To '.$this->source->name;
        }

        return [
            'trx_id' => $this->trx_id,
            'amount' => number_format($this->amount, 2).' MMK',
            'type' => $this->type,
            'title' => $title,
            'date' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
