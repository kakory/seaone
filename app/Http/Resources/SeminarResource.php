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
            'course_id' => $this->course_id,
            'name' => $this->name,
            'lecturer' => $this->lecturer,
            'total_quota' => $this->quota,
            'remaining_quota' => $this->customers->count(),
            'start_date_at' => $this->start_date_at,
            'start_time_at' => $this->start_time_at,
            'end_date_at' => $this->end_date_at,
            'end_time_at' => $this->end_time_at,
            'closing_date_at' => $this->closing_date_at,
            'closing_time_at' => $this->closing_time_at,
            'classroom' => $this->classroom,
            'qrcode' => $this->qrcode,
            'note' => $this->course->note,
        ];
    }
}
