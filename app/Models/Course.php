<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $guarded = [];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function grades()
    {
        return $this->belongsTo(Grade::class);
    }

    public function scopeFilter(Builder $query, $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->whereAny([
                'name',
                'code',
            ], 'REGEXP', $search)
                ->orWhereHas('faculty', fn($query) => $query->whereAny(['name'], 'REGEXP', $search))
                ->orWhereHas('teacher.user', fn($query) => $query->whereAny(['name', 'email'], 'REGEXP', $search))
                ->orWhereHas('departement', fn($query) => $query->whereAny(['name'], 'REGEXP', $search))
            ;
        });
    }

    public function scopeSorting(Builder $query, $sorts)
    {
        $query->when($sorts['field'] ?? null && $sorts['direction'] ?? null, function ($query) use ($sorts) {
            match ($sorts['field']) {
                'faculty_id' => $query->join('faculties', 'courses.faculty_id', '=', 'faculties.id')
                    ->orderBy('faculties.name', $sorts['direction']),
                'departement_id' => $query->join('departements', 'courses.departement_id', '=', 'departements.id')
                    ->orderBy('departements.name', $sorts['direction']),
                'name' => $query
                    ->leftJoin('teachers', 'teachers.id', '=', 'courses.teacher_id')
                    ->leftJoin('users', 'teachers.user_id', '=', 'users.id')
                    ->orderBy('users.name', $sorts['direction']),
                default => $query->orderBy($sorts['field'], $sorts['direction'])
            };
        });
    }
}
