<?php

namespace App\Models;

use App\Enums\ScheduleDay;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $guarded = [];

    protected function casts()
    {
        return [
            'day_of_week' => ScheduleDay::class,
        ];
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }



    public function scopeFilter(Builder $query, $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->whereAny([
                'start_time',
                'end_time',
                'day_of_week',
            ], 'REGEXP', $search)
                ->orWhereHas('level', fn($query) => $query->whereAny(['name'], 'REGEXP', $search))
                ->orWhereHas('classroom', fn($query) => $query->whereAny(['name'], 'REGEXP', $search))
                ->orWhereHas('course', fn($query) => $query->whereAny(['name'], 'REGEXP', $search))
            ;
        });
    }

    public function scopeSorting(Builder $query, $sorts)
    {
        $query->when($sorts['field'] ?? null && $sorts['direction'] ?? null, function ($query) use ($sorts) {
            match ($sorts['field']) {
                'level_id' => $query->join('levels', 'schedules.level_id', '=', 'levels.id')
                    ->orderBy('levels.name', $sorts['direction']),
                'course_id' => $query->join('courses', 'schedules.course_id', '=', 'courses.id')
                    ->orderBy('courses.name', $sorts['direction']),
                'classroom_id' => $query->join('classrooms', 'schedules.classroom_id', '=', 'classrooms.id')
                    ->orderBy('classrooms.name', $sorts['direction']),
                'academic_year_id' => $query->join('academic_years', 'schedules.academic_year_id', '=', 'academic_years.id')
                    ->orderBy('academic_years.name', $sorts['direction']),
                default => $query->orderBy($sorts['field'], $sorts['direction'])
            };
        });
    }
}
