<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $guarded = [];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
