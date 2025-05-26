<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use Sluggable;
    protected $guarded = [];

    protected function code(): Attribute
    {
        return Attribute::make(
            get: fn($value) => strtoupper($value),
            set: fn($value) => strtolower($value),
        );
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function scopeFilter(Builder $query, $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->whereAny([
                'name',
                'code',
            ], 'REGEXP', $search)
                ->orWhereHas('faculty', fn($query) => $query->where('name', 'REGEXP', $search));
        });
    }

    // public function scopeSorting(Builder $query, $sorts)
    // {
    //     $query->when($sorts['field'] ?? null && $sorts['direction'] ?? null, function ($query) use ($sorts) {
    //         match ($sorts['field']) {
    //             'faculty_id' => $query->join('faculties', 'departements.faculty_id', '=', 'faculties.id')
    //                 ->orderBy('faculties.name', $sorts['direction']),
    //             default =>  $query->orderBy($sorts['field'], $sorts['direction']),
    //         };
    //     });
    // }

    public function scopeSorting(Builder $query, $sorts)
    {
        $query->when($sorts['field'] ?? null && $sorts['direction'] ?? null, function ($query) use ($sorts) {
            match ($sorts['field']) {
                'faculty_id' => $query
                    ->join('faculties', 'departements.faculty_id', '=', 'faculties.id')
                    ->select([
                        'departements.id',
                        'departements.name',
                        'departements.faculty_id',
                        'departements.code',
                        'departements.slug',
                        'departements.created_at'
                    ])
                    ->orderBy('faculties.name', $sorts['direction']),
                default => $query->orderBy($sorts['field'], $sorts['direction']),
            };
        });
    }
}
