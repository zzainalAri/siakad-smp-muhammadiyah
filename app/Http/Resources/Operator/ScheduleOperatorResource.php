<?php

namespace App\Http\Resources\Operator;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleOperatorResource extends JsonResource
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
            'quote' => $this->quote,
            'created_at' => $this->created_at,
            'taken_quota' => $this->taken_quota,
            'academicYear' => $this->whenLoaded('academicYear', [
                'id' => $this->academicYear?->id,
                'name' => $this->academicYear?->name,
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
                'credit' => $this->course?->credit,
                'teacher' => $this->course?->teacher?->name,
            ]),
        ];
    }
}
