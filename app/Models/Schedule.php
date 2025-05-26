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

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class);
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

    public function studyPlans()
    {
        return $this->belongsToMany(StudyPlan::class, 'study_plan_schedule')->withTimestamps();
    }

    public function scopeFilter(Builder $query, $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->whereAny([
                'start_time',
                'end_time',
                'day_of_week',
            ], 'REGEXP', $search)
                ->orWhereHas('faculty', fn($query) => $query->whereAny(['name'], 'REGEXP', $search))
                ->orWhereHas('departement', fn($query) => $query->whereAny(['name'], 'REGEXP', $search))
                ->orWhereHas('classroom', fn($query) => $query->whereAny(['name'], 'REGEXP', $search))
                ->orWhereHas('course', fn($query) => $query->whereAny(['name'], 'REGEXP', $search))
            ;
        });
    }

    public function scopeSorting(Builder $query, $sorts)
    {
        $query->when($sorts['field'] ?? null && $sorts['direction'] ?? null, function ($query) use ($sorts) {
            match ($sorts['field']) {
                'faculty_id' => $query->join('faculties', 'schedules.faculty_id', '=', 'faculties.id')
                    ->orderBy('faculties.name', $sorts['direction']),
                'departement_id' => $query->join('departements', 'schedules.departement_id', '=', 'departements.id')
                    ->orderBy('departements.name', $sorts['direction']),
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
