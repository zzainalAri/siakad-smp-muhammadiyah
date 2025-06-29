<?php

namespace App\Models;

use App\Enums\StudyPlanStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class StudyPlan extends Model
{
    protected $guarded = [];

    protected function casts()
    {
        return [
            'status' => StudyPlanStatus::class,
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'study_plan_schedule')->withTimestamps();
    }

    public function scopeApproved(Builder $query)
    {
        $query->where('status', StudyPlanStatus::APPROVED->value);
    }
    public function scopePending(Builder $query)
    {
        $query->where('status', StudyPlanStatus::PENDING->value);
    }

    public function scopeReject(Builder $query)
    {
        $query->where('status', StudyPlanStatus::REJECT->value);
    }

    public function scopeFilter(Builder $query, $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->whereAny([
                'academic_year_id',
                'semester'
            ], 'REGEXP', $search);
        });
    }

    public function scopeSorting(Builder $query, $sorts)
    {
        $query->when($sorts['field'] ?? null && $sorts['direction'] ?? null, function ($query) use ($sorts) {
            $query->orderBy($sorts['field'], $sorts['direction']);
        });
    }
}
