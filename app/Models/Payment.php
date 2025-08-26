<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];
    public function fee()
    {
        return $this->belongsTo(Fee::class);
    }


    public function scopeFilter(Builder $query, $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->whereAny([
                'status',
                'transaction_code',
                'amount_paid',
            ], 'REGEXP', $search)
                ->orWhereHas('fee', fn($query) => $query->whereAny(['fee_code'], 'REGEXP', $search))
            ;
        });
    }

    public function scopeSorting(Builder $query, $sorts)
    {
        $query->when($sorts['field'] ?? null && $sorts['direction'] ?? null, function ($query) use ($sorts) {
            match ($sorts['field']) {
                'fee_code' => $query->join('fees', 'payments.fee_id', '=', 'fees.id')
                    ->orderBy('fees.fee_code', $sorts['direction']),

                default => $query->orderBy($sorts['field'], $sorts['direction'])
            };
        });
    }
}
