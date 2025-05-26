<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function feeGroup()
    {
        return $this->belongsTo(FeeGroup::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function scopeFilter(Builder $query, $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('status', 'REGEXP', $search)
                ->orWhereHas('academicYear', fn($query) => $query->whereAny(['name'], 'REGEXP', $search))
                ->orWhereHas('student.user', fn($query) => $query->whereAny(['name', 'email'], 'REGEXP', $search))
            ;
        });
    }

    public function scopeSorting(Builder $query, $sorts)
    {
        $query->when($sorts['field'] ?? null && $sorts['direction'] ?? null, function ($query) use ($sorts) {
            match ($sorts['field']) {
                'academic_year_id' => $query->join('academic_years', 'fees.faculty_id', '=', 'academic_years.id')
                    ->orderBy('faculties.name', $sorts['direction']),
                'faculty_id' => $query->join('faculties', 'fees.faculty_id', '=', 'faculties.id')
                    ->orderBy('faculties.name', $sorts['direction']),
                'departement_id' => $query->join('departements', 'fees.departement_id', '=', 'departements.id')->orderBy('departements.name', $sorts['direction']),
                'name' => $query
                    ->leftJoin('students', 'students.id', '=', 'fees.student_id')
                    ->leftJoin('users', 'students.user_id', '=', 'users_id')
                    ->orderBy('users.name', $sorts['direction']),
                default => $query->orderBy($sorts['field'], $sorts['direction'])
            };
        });
    }
}
