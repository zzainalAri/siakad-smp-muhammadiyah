<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyResultGrade extends Model
{
    protected $guarded = [];

    public function studyResult()
    {
        return $this->belongsTo(StudyResult::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
