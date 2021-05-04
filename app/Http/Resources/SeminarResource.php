<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SeminarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'lecturer' => $this->lecturer,
            'total_quota' => $this->quota,
            'remaining_quota' => $this->customers->count(),
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'closing_at' => $this->closing_at,
            'classroom' => $this->classroom,
            'qrcode' => $this->qrcode,
        ];
    }
}
