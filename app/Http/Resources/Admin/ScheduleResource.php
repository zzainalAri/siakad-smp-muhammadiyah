<?php

namespace App\Http\Resources\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'start_time' => Carbon::parse($this->start_time)->format('H:i'),
            'end_time' => Carbon::parse($this->end_time)->format('H:i'),
            'day_of_week' => $this->day_of_week,
            'created_at' => $this->created_at,
            'level' => $this->whenLoaded('level', [
                'id' => $this->level?->id,
                'name' => $this->level?->name,
            ]),
            'classroom' => $this->whenLoaded('classroom', [
                'id' => $this->classroom?->id,
                'name' => $this->classroom?->name,
                'slug' => $this->classroom?->slug,
            ]),
            'course' => $this->whenLoaded('course', [
                'id' => $this->course?->id,
                'name' => $this->course?->name,
                'code' => $this->course?->code,
            ]),
        ];
    }
}