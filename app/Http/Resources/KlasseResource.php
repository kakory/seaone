<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KlasseResource extends JsonResource
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
            'name' => $this->course->name,
            'lecturer' => $this->lecturer,
            'total_quota' => $this->quota,
            'remaining_quota' => $this->customers->count(),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'closing_time' => $this->closing_time,
            'classroom' => $this->classroom,
            'qrcode' => $this->qrcode,
        ];
    }
}
