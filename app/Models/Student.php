<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function feeGroup()
    {
        return $this->belongsTo(FeeGroup::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function studyPlans()
    {
        return $this->hasMany(StudyPlan::class);
    }

    public function studyResults()
    {
        return $this->hasMany(StudyResult::class);
    }


    public function scopeFilter(Builder $query, $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->whereAny([
                'student_number',
                'semester',
                'batch',
            ], 'REGEXP', $search)
                ->orWhereHas('user', fn($query) => $query->whereAny(['name', 'email'], 'REGEXP', $search))
                ->orWhereHas('faculty', fn($query) => $query->whereAny(['name'], 'REGEXP', $search))
                ->orWhereHas('departement', fn($query) => $query->whereAny(['name'], 'REGEXP', $search))
            ;
        });
    }

    public function scopeSorting(Builder $query, $sorts)
    {
        $query->when($sorts['field'] ?? null && $sorts['direction'] ?? null, function ($query) use ($sorts) {
            match ($sorts['field']) {
                'faculty_id' => $query->join('faculties', 'students.faculty_id', '=', 'faculties.id')
                    ->orderBy('faculties.name', $sorts['direction']),
                'departement_id' => $query->join('departements', 'students.departement_id', '=', 'departements.id')
                    ->orderBy('departements.name', $sorts['direction']),
                'name' => $query->join('users', 'students.user_id', '=', 'users.id')
                    ->orderBy('users.name', $sorts['direction']),
                'email' => $query->join('users', 'students.user_id', '=', 'users.id')
                    ->orderBy('users.email', $sorts['direction']),
                'fee_group_id' => $query->join('fee_groups', 'students.fee_group_id', '=', 'fee_groups.id')
                    ->orderBy('fee_groups.group', $sorts['direction']),
                'classroom_id' => $query->join('classrooms', 'students.classroom_id', '=', 'classrooms.id')
                    ->orderBy('classrooms.name', $sorts['direction']),
                default => $query->orderBy($sorts['field'], $sorts['direction'])
            };
        });
    }
}
