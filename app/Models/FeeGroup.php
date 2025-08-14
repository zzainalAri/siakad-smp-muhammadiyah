<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeGroup extends Model
{
    protected $guarded = [];

    use HasFactory;

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function scopeFilter(Builder $query, $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->whereAny([
                'amount',
            ], 'REGEXP', $search)
                ->orWhereHas('level', fn($query) => $query->whereAny(['name'], 'REGEXP', $search))
            ;
        });
    }

    public function scopeSorting(Builder $query, $sorts)
    {
        $query->when($sorts['field'] ?? null && $sorts['direction'] ?? null, function ($query) use ($sorts) {
            match ($sorts['field']) {
                'level_id' => $query->join('levels', 'fee_groups.level_id', '=', 'levels.id')
                    ->orderBy('levels.name', $sorts['direction']),
                default => $query->orderBy($sorts['field'], $sorts['direction'])
            };
        });
    }
}
